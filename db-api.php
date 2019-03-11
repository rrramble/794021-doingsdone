<?php
include_once('functions.php');

class DbApi
{
    private $FILE_PUB_FOLDER = DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR;
    private $URL_FILE_FOLDER = '/pub/';

    private $DbSettings = [
        'HOST' => '127.0.0.1',
        'USERNAME' => 'root',
        'PASSWORD' => '',
        'DB_NAME' => '794021_doingsdone',
        'ENCODING' => 'utf8'
    ];
    private $SqlQuerySTMT = [
        'ADD_TASK' => 'INSERT INTO tasks ' .
          '(project_id, title, due_date, author_user_id, file_path)' .
          'VALUES(?, ?, ?, ?, ?)',
        'ADD_PROJECT' => 'INSERT INTO projects ' .
            '(title, author_user_id)' .
            'VALUES(?, ?)',
        'ADD_USER' => 'INSERT INTO users ' .
          '(email, name, password_hash)' .
          'VALUES(?, ?, ?)',
        'TASK_DONE' => 'UPDATE tasks ' .
          'SET state_id = ? ' .
          'WHERE id = ?',
        ];

    protected $handler;

    function __construct($userId)
    {
        $this->handler = mysqli_connect(
            $this->DbSettings['HOST'],
            $this->DbSettings['USERNAME'],
            $this->DbSettings['PASSWORD'],
            $this->DbSettings['DB_NAME']
        );

        if (!$this->isConnected()) {
            $this->throwDbException();
        };

        if ($this->handler) {
            mysqli_set_charset($this->handler, $this->DbSettings['ENCODING']);
        };

        $this->userId = $userId;

    }

    public function addProject($values)
    {
        $stmt = mysqli_prepare($this->handler, $this->SqlQuerySTMT['ADD_PROJECT']);
        if (!$stmt) {
            $this->throwDbException();
        };
        $title = $values["title"];
        $authorId = $values["authorId"];

        $result = mysqli_stmt_bind_param($stmt, 'si',
          $title,
          $authorId
        );
        if (!$result) {
            $this->throwDbException();
        };


        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            $this->throwDbException();
        };
        mysqli_stmt_close($stmt);
        return true;
    }

    public function addTask($values)
    {
        $stmt = mysqli_prepare($this->handler, $this->SqlQuerySTMT['ADD_TASK']);
        if (!$stmt) {
            $this->throwDbException();
        };
        $result = mysqli_stmt_bind_param($stmt, 'issis',
          $projectId,
          $title,
          $dueDate,
          $authorUserId,
          $savedFileUrlPath
        );
        if (!$result) {
            $this->throwDbException();
        };

        $projectId = (integer)$values['projectId'];
        $title = $values['title'];
        $dueDate = convertDateReadableToHtmlFormInput($values['dueDate']);
        $authorUserId = (integer)$values['id'];
        $savedFileUrlPath = $this->saveFileFromTempFolder($values['savedFileName'], $values['originalFilePathName']);

        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            $this->throwDbException();
        };
        mysqli_stmt_close($stmt);
        return true;
    }

    public function addUser($user)
    {
        $stmt = mysqli_prepare($this->handler, $this->SqlQuerySTMT['ADD_USER']);
        if (!$stmt) {
            $this->throwDbException();
        };
        $result = mysqli_stmt_bind_param($stmt, 'sss',
          $email,
          $userName,
          $passwordHash
        );
        if (!$result) {
            $this->throwDbException();
        };

        $email = $user['email'];
        $userName = $user['userName'];
        $passwordHash = $user['passwordHash'];

        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            $this->throwDbException();
        };
        mysqli_stmt_close($stmt);
    }

    function getUserPasswordHash($email)
    {
        $emailEscaped = mysqli_real_escape_string($this->handler, (string)$email);
        $query  = "SELECT password_hash FROM users WHERE email = '$emailEscaped'";
        $result = mysqli_query($this->handler, $query);
        if (!$result) {
            return NULL;
        };
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $result ? $result[0]['password_hash'] : null;
    }

    function getProjects()
    {
        $userIdEscaped = mysqli_real_escape_string($this->handler, (string)$this->userId);
        $query  = "SELECT * FROM projects WHERE author_user_id = '$userIdEscaped'";
        $result = mysqli_query($this->handler, $query);
        if (!$result) {
            return [];
        };
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getProjectTitles()
    {
        $projects = $this->getProjects();
        $result = array_map(function($project) {
            return $project['title'];
        }, $projects);
        return $result;
    }

    function getTasks($currentUserId)
    {
        $userIdEscaped = mysqli_real_escape_string($this->handler, (string)$currentUserId);
        $query  = "SELECT * FROM tasks WHERE author_user_id = '$userIdEscaped'";

        $result = mysqli_query($this->handler, $query);
        if (!$result) {
            return NULL;
        };
        return $result;
    }

    function getUserDataByEmail($email)
    {
        $emailEscaped = mysqli_real_escape_string($this->handler, (string)$email);
        $query = "SELECT id, name, email FROM users WHERE email = '$emailEscaped'";
        $result = mysqli_query($this->handler, $query);
        if (!$result) {
            $this->throwDbException();
        };

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if (count($rows) <= 0) {
            $this->throwDbException();
        };

        return [
            "id" => $rows[0]["id"],
            "email" => $rows[0]["email"],
            "userName" => $rows[0]["name"],
        ];
    }

    function isConnected()
    {
        return (boolean)$this->handler;
    }

    function isValidUserCredential($email, $password)
    {
        $dbPasswordHash = $this->getUserPasswordHash($email);
        return password_verify($password, $dbPasswordHash);
    }

    function isProjectIdExists($id)
    {
        if ($id === NULL) {
            return true;
        }
        $idEscaped = mysqli_real_escape_string($this->handler, (string)$id);
        $query = "SELECT id FROM projects WHERE id = '$idEscaped'";
        $result = mysqli_query($this->handler, $query);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return count($rows) > 0;
    }

    public function isTaskStateExist($stateId)
    {
        $stateIdEscaped = mysqli_real_escape_string($this->handler, (string)$stateId);
        $query = "SELECT id FROM task_states WHERE id = '$stateIdEscaped'";
        $result = mysqli_query($this->handler, $query);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return count($rows) > 0;
    }

    public function isUserEmailExist($email)
    { // DRY principle violated! Rewrite! See also: 'isProjectIdExists'
        $emailEscaped = mysqli_real_escape_string($this->handler, (string)$email);
        $query = "SELECT email FROM users WHERE email = '$emailEscaped'";
        $result = mysqli_query($this->handler, $query);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return count($rows) > 0;
    }

    private function isUserIdExist($userId)
    {
        $userIdEscaped = mysqli_real_escape_string($this->handler, (string)$userId);
        $query = "SELECT id FROM users WHERE id = '$userIdEscaped'";
        $result = mysqli_query($this->handler, $query);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return count($rows) > 0;
    }

    private function saveFileFromTempFolder($tempFileNamePath, $originalFileNamePath)
    {
        if (!isset($tempFileNamePath) || strlen($tempFileNamePath) <= 0) {
            return null;
        };
        $fileExtension = pathinfo($originalFileNamePath, PATHINFO_EXTENSION);
        $fileExtension = $fileExtension ? '.' . $fileExtension : '';
        $filename = uniqid() . $fileExtension;

        $newFilePathName = __DIR__ . $this->FILE_PUB_FOLDER . $filename;
        $isSaved = move_uploaded_file($tempFileNamePath, $newFilePathName);
        if (!$isSaved) {
            return '';
        };

        $url = $this->URL_FILE_FOLDER . $filename;
        return $url;
    }

    public function setTaskIsDone($taskState)
    {
        if (!$taskState) {
            return false;
        };

        $taskIsDoneState = (integer)$taskState["isDone"];
        $taskId = (integer)$taskState["id"];

        if (!$this->isTaskStateExist($taskIsDoneState)) {
            return false;
        };

        $stmt = mysqli_prepare($this->handler, $this->SqlQuerySTMT['TASK_DONE']);
        if (!$stmt) {
            $this->throwDbException();
        };

        $result = mysqli_stmt_bind_param($stmt, 'ii',
            $taskIsDoneState,
            $taskId
        );
        if (!$result) {
            $this->throwDbException();
        };

        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    }

    function throwDbException()
    {
        throw new Exception(mysqli_connect_error());
    }

} // class DbApi
