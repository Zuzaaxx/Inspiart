<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Project.php';

class ProjectRepository extends Repository
{
    public function getProject(int $id): ?Project
    {
        $stmt = $this->database->Connect()->prepare('
            SELECT * FROM users_gallery WHERE id = :id
        ');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $idea = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($idea == false) {
            return null;
            //throw new Exception("User not found");
        }

        return new Project(
            $idea['title'],
            $idea['description'],
            $idea['path']
        );
    }

    public function addProject(Project $project): void
    {
        $date = new DateTime();
        $stmt = $this->database->Connect()->prepare('
            INSERT INTO users_gallery (title, description, path, date, user_id, idea_id)
            values (?, ?, ?, ?, ?, ?)
        ');

        //TODO get user id, idea id
        $user_id = 1;
        $idea_id = 1;

        $stmt->execute([
            $project->getTitle(),
            $project->getDescription(),
            $project->getImage(),
            $date->format('D-M-Y'),
            $user_id,
            $idea_id
        ]);
    }

    public function getProjects(): array
    {
        $result = [];
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users_gallery;
        ');
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($projects as $project) {
            $result[] = new Project(
                $project['title'],
                $project['description'],
                $project['path']
            );
        }
        return $result;
    }

    public function getProjectByTitle(string $searchString)
    {
        $searchString = '%' . strtolower($searchString) . '%';
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users_gallery WHERE LOWER(title) LIKE :search OR LOWER(description) LIKE :search
        ');
        $stmt->bindParam(':search', $searchString, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}