<?php

namespace Auth;

use Database\Database;
use PDO;

class User
{
    protected const string TABLE = 'users';

    private readonly PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findOrCreateUser($githubData)
    {
        $sql = "
          SELECT *
          FROM " . static::TABLE . "
          WHERE `github_id` = ?
        ";

        $query = $this->db->prepare($sql);
        $query->execute([$githubData['id']]);

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $sql = "
                INSERT INTO " . static::TABLE . "
                (`github_id`, `username`, `email`)
                VALUES (?, ?, ?)
            ";

            $query = $this->db->prepare($sql);
            $query->execute([$githubData['id'], $githubData['login'], $githubData['email']]);

            $user = $this->findUserByGithubId($githubData['id']);
        }

        return $user;
    }

    public function findUserByGithubId($githubId)
    {
        $sql = "
            SELECT *
            FROM " . static::TABLE . "
            WHERE `github_id` = ?
        ";

        $query = $this->db->prepare($sql);
        $query->execute([$githubId]);

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}