<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/Idea.php';


class IdeaController extends AppController
{
    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_FILE_TYPES = ['image/png', 'image/jpeg', 'image/jpg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';

    private $messages = [];

    public function addIdea()
    {
        if ($this->isPost() && is_uploaded_file($_FILES['file']['tmp_name']) && $this->validate($_FILES['file'])) {
            move_uploaded_file(
                $_FILES['file']['tmp_name'],
                dirname(__DIR__).self::UPLOAD_DIRECTORY.$_FILES['file']['name']
            );

            $idea = new Idea($_POST['title'], $_POST['description'], $_FILES['file']['name']);

            //$url = "http://$_SERVER[HTTP_HOST]";
            //header("Location: {$url}/gallery");

            return $this->render('gallery', ['messages' => $this->messages, 'idea' => $idea]);
        }

        return $this->render('add-idea', ['messages' => $this->messages]);
    }

    private function validate(array $file) : bool
    {
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $this->messages[] = "The uploaded file exceeds the maximum allowed size.";
            return false;
        }

        if (!isset($file['type']) && in_array($file['type'], self::SUPPORTED_FILE_TYPES)) {
            $this->messages[] = "File type is not supported.";
            return false;
        }

        return true;
    }











}