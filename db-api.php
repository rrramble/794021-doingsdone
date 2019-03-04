<?php

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
          '(project_id, state_id, title, due_date, author_user_id, file_path)' .
          'VALUES(?, 0, ?, ?, ?, ?)',
    ];

    protected $handler;

    function __construct()
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
    }

    public function addTask($values)
    {
        $stmt = mysqli_prepare($this->handler, $this->SqlQuerySTMT['ADD_TASK']);
        if (!$stmt) {
            $this->throwDbException();
        };
        $dueDate = strtotime($values['dueDate']);
        $savedFilenamePath = $this->saveFileFromTempFolder($values['savedFileName'], $values['originalFilePathName']);

        $isBound = mysqli_stmt_bind_param($stmt, 'isiis',
          $values['projectId'],
          $values['title'],
          $dueDate,
          $values['id'],
          $savedFilenamePath
        );
        if (!$isBound) {
            $this->throwDbException();
        };

    }

    function getProjects()
    {
        $query  = "SELECT * FROM projects";
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

    function isConnected()
    {
        return (boolean)$this->handler;
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

    private function saveFileFromTempFolder($tempFileNamePath, $originalFileNamePath)
    {
        if (!isset($tempFileNamePath) || strlen($tempFileNamePath) <= 0) {
            return '';
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

    function throwDbException()
    {
        throw new Exception(mysqli_connect_error());
    }

} // class DbApi
