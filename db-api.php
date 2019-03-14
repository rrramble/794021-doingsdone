<?php
include_once('functions.php');

class DbApi
{
    const FOLDER_NAME = 'pub';
    const FILE_PUB_FOLDER = DIRECTORY_SEPARATOR . self::FOLDER_NAME . DIRECTORY_SEPARATOR;
    const URL_FILE_FOLDER = '/' . self::FOLDER_NAME . '/';

    const DbSettings = [
        'HOST' => '127.0.0.1',
        'USERNAME' => 'root',
        'PASSWORD' => '',
        'DB_NAME' => '794021_doingsdone',
        'ENCODING' => 'utf8'
    ];

    const SqlQuerySTMT = [
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
          'SET state_id = ?, date_completed = ? ' .
          'WHERE id = ?',
        ];

    private $handler = null;


    /**
     * __construct
     * 
     * @param  integer $userId
     *
     * @return self
     */
    public function __construct($userId = 0)
    {
        $this->handler = mysqli_connect(
            self::DbSettings['HOST'],
            self::DbSettings['USERNAME'],
            self::DbSettings['PASSWORD'],
            self::DbSettings['DB_NAME']
        );

        if (!$this->isConnected()) {
            $this->throwDbException();
        };

        if ($this->handler) {
            mysqli_set_charset($this->handler, self::DbSettings['ENCODING']);
        };

        $this->userId = $userId;
    }


    /**
     * addProject
     *
     * @param  array $values
     *
     * @return boolean
     */
    public function addProject($values)
    {
        if (
            !isset($values["title"]) ||
            !isset($values["authorId"])
        ) {
            return false;
        };

        $stmt = mysqli_prepare($this->handler, self::SqlQuerySTMT['ADD_PROJECT']);
        if (!$stmt) {
            return false;
        };

        $title = $values["title"];
        $authorId = $values["authorId"];

        $result = mysqli_stmt_bind_param($stmt, 'si',
          $title,
          $authorId
        );
        if (!$result) {
            return false;
        };

        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            return false;
        };

        mysqli_stmt_close($stmt);
        return true;
    }


    /**
     * addTask
     *
     * @param  array $values
     *
     * @return boolean
     */
    public function addTask($values)
    {
        $stmt = mysqli_prepare($this->handler, self::SqlQuerySTMT['ADD_TASK']);
        if (!$stmt) {
            return false;
        };

        if (
            !isset($values["projectId"]) ||
            !isset($values["title"]) ||
            !isset($values["dueDate"]) ||
            !isset($values["userId"]) ||
            !isset($values["savedFileName"]) ||
            !isset($values["originalFileName"])
        ) {
            return false;
        };

        $projectId = (integer)$values['projectId'];
        $title = $values['title'];
        $dueDate = convertDateReadableToHtmlFormInput($values['dueDate']);
        $authorUserId = (integer)$values['userId'];
        $savedFileUrlPath = $this->saveFileFromTempFolder($values['savedFileName'], $values['originalFileName']);

        $result = mysqli_stmt_bind_param($stmt, 'issis',
            $projectId,
            $title,
            $dueDate,
            $authorUserId,
            $savedFileUrlPath
        );
        if (!$result) {
            return false;
        };

        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            return false;
        };

        mysqli_stmt_close($stmt);
        return true;
    }


    /**
     * addUser
     *
     * @param  array $user
     *
     * @return boolean
     */
    public function addUser($user)
    {
        $stmt = mysqli_prepare($this->handler, self::SqlQuerySTMT['ADD_USER']);
        if (!$stmt) {
            return false;
        };

        if (
            !isset($user["email"]) ||
            !isset($user["userName"]) ||
            !isset($user["passwordHash"])
        ) {
            return false;
        };

        $email = $user['email'];
        $userName = $user['userName'];
        $passwordHash = $user['passwordHash'];

        $result = mysqli_stmt_bind_param($stmt, 'sss',
            $email,
            $userName,
            $passwordHash
        );
        if (!$result) {
            return false;
        };

        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            return false;
        };

        mysqli_stmt_close($stmt);
    }


    /**
     * getUserPasswordHash
     *
     * @param  string $email
     *
     * @return string|null
     */
    function getUserPasswordHash($email)
    {
        $emailEscaped = mysqli_real_escape_string($this->handler, (string)$email);
        $query  = "SELECT password_hash FROM users WHERE email = '$emailEscaped'";

        $result = mysqli_query($this->handler, $query);
        if (!$result) {
            return null;
        };

        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $result ? $result[0]['password_hash'] : null;
    }


    /**
     * getProjects
     *
     * @return array
     */
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


    /**
     * getTasks
     *
     * @return array
     */
    function getTasks()
    {
        $userIdEscaped = mysqli_real_escape_string($this->handler, (string)$this->userId);
        $query  = "SELECT * FROM tasks WHERE author_user_id = '$userIdEscaped'";

        $result = mysqli_query($this->handler, $query);
        if (!$result) {
            return [];
        };
        return $result;
    }


    /**
     * getUserDataByEmail
     *
     * @param  string $email
     *
     * @return array
     */
    function getUserDataByEmail($email)
    {
        $emailEscaped = mysqli_real_escape_string($this->handler, (string)$email);
        $query = "SELECT id, name, email FROM users WHERE email = '$emailEscaped'";
        $result = mysqli_query($this->handler, $query);
        if (!$result) {
            return null;
        };

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if (count($rows) <= 0) {
            return null;
        };

        if (
            !isset($rows[0]) ||
            !isset($rows[0]["id"]) ||
            !isset($rows[0]["email"]) ||
            !isset($rows[0]["name"])
        ) {
            return null;
        };

        return [
            "id" => $rows[0]["id"],
            "email" => $rows[0]["email"],
            "userName" => $rows[0]["name"],
        ];
    }


    /**
     * isConnected
     *
     * @return boolean
     */
    function isConnected()
    {
        return (boolean)$this->handler;
    }


    /**
     * isValidUserCredential
     *
     * @param  string $email
     * @param  string $password
     *
     * @return boolean
     */
    function isValidUserCredential($email, $password)
    {
        $dbPasswordHash = $this->getUserPasswordHash($email);
        return password_verify($password, $dbPasswordHash);
    }


    /**
     * isProjectIdExistForCurrentUser
     *
     * @param  integer $projectId
     *
     * @return boolean
     */
    function isProjectIdExistForCurrentUser($projectId)
    {
        if ($projectId === NULL) {
            return true;
        }
        $projectIdEscaped = mysqli_real_escape_string($this->handler, (string)$projectId);
        $userIdEscaped = mysqli_real_escape_string($this->handler, (string)$this->userId);

        $query = "SELECT id FROM projects WHERE id = '$projectIdEscaped' AND author_user_id = '$userIdEscaped'";
        $result = mysqli_query($this->handler, $query);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return count($rows) > 0;
    }


    /**
     * isTaskStateExist
     *
     * @param  integer $stateId
     *
     * @return boolean
     */
    public function isTaskStateExist($stateId)
    {
        $stateIdEscaped = mysqli_real_escape_string($this->handler, (string)$stateId);
        $query = "SELECT id FROM task_states WHERE id = '$stateIdEscaped'";
        $result = mysqli_query($this->handler, $query);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return count($rows) > 0;
    }


    /**
     * isUserEmailExist
     *
     * @param  string $email
     *
     * @return boolean
     */
    public function isUserEmailExist($email)
    {
        $emailEscaped = mysqli_real_escape_string($this->handler, (string)$email);
        $query = "SELECT email FROM users WHERE email = '$emailEscaped'";
        $result = mysqli_query($this->handler, $query);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return count($rows) > 0;
    }


    /**
     * saveFileFromTempFolder
     *
     * @param  string $tempFileNamePath
     * @param  string $originalFileNamePath
     *
     * @return string|null
     */
    private function saveFileFromTempFolder($tempFileNamePath, $originalFileNamePath)
    {
        if (!isset($tempFileNamePath) || strlen($tempFileNamePath) <= 0) {
            return null;
        };
        $fileExtension = pathinfo($originalFileNamePath, PATHINFO_EXTENSION);
        $fileExtension = $fileExtension ? '.' . $fileExtension : '';
        $filename = uniqid() . $fileExtension;

        $newFilePathName = __DIR__ . self::FILE_PUB_FOLDER . $filename;
        $isSaved = move_uploaded_file($tempFileNamePath, $newFilePathName);
        if (!$isSaved) {
            return null;
        };

        $url = self::URL_FILE_FOLDER . $filename;
        return $url;
    }

    /**
     * @param  string $text
     * @return array
     */
    public function searchTasks($text)
    {
        $textEscaped = mysqli_real_escape_string($this->handler, (string)$text);
        $query = "SELECT * FROM tasks WHERE MATCH (title) AGAINST ('$textEscaped');";
        $result = mysqli_query($this->handler, $query);
        if (!$result) {
            return [];
        };

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $rows;
    }

    /**
     * setTaskIsDone
     *
     * @param  array $taskState
     *
     * @return boolean
     */
    public function setTaskIsDone($taskState)
    {
        if (
            !isset($taskState["isDone"]) ||
            !isset($taskState["id"])
        ) {
            return false;
        };

        $taskIsDoneState = (integer)$taskState["isDone"];
        $dateCompleted = $taskIsDoneState ? date("Y-m-d") : NULL;
        $taskId = (integer)$taskState["id"];

        if (!$this->isTaskStateExist($taskIsDoneState)) {
            return false;
        };

        $stmt = mysqli_prepare($this->handler, self::SqlQuerySTMT['TASK_DONE']);
        if (!$stmt) {
            return false;
        };

        $result = mysqli_stmt_bind_param($stmt, 'isi',
            $taskIsDoneState,
            $dateCompleted,
            $taskId
        );
        if (!$result) {
            return false;
        };

        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            return false;
        };

        mysqli_stmt_close($stmt);
        return true;
    }


    /**
     * throwDbException
     *
     * @return void
     */
    function throwDbException()
    {
        throw new Exception(mysqli_connect_error());
    }

} // class DbApi
