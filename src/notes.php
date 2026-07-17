<?php

class notes {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($user_id, $title, $body) {
        $stmt = $this->pdo->prepare("insert into notes (user_id, title, body) values (?, ?, ?)");
        return $stmt->execute([$user_id, $title, $body]);
    }

    public function get_all($user_id) {
        $stmt = $this->pdo->prepare("select * from notes where user_id = ? order by id desc");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function get_one($id, $user_id) {
        $stmt = $this->pdo->prepare("select * from notes where id = ? and user_id = ?");
        $stmt->execute([$id, $user_id]);
        return $stmt->fetch();
    }

    public function update($id, $user_id, $title, $body) {
        $stmt = $this->pdo->prepare("update notes set title = ?, body = ? where id = ? and user_id = ?");
        return $stmt->execute([$title, $body, $id, $user_id]);
    }

    public function delete($id, $user_id) {
        $stmt = $this->pdo->prepare("delete from notes where id = ? and user_id = ?");
        return $stmt->execute([$id, $user_id]);
    }
}
