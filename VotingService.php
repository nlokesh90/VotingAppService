<?php

require_once 'IVotingService.php';

class VotingService implements IVotingService
{
    private $dbfile = 'data/vote_db_pdo.sqlite';
    private $db;

    public function __construct(){
        //open the database
        $this->db = new \PDO('sqlite:' . $this->dbfile);

        //create the user table if not exists
        $this->db->exec("CREATE TABLE IF NOT EXISTS user (Id INTEGER PRIMARY KEY, UserName TEXT, HasVoted INTEGER)");

        //create the item table if not exists
        $this->db->exec("CREATE TABLE IF NOT EXISTS item (ItemId INTEGER PRIMARY KEY, ItemName TEXT)");

        //create the item table if not exists
        $this->db->exec("CREATE TABLE IF NOT EXISTS votes (VotingId INTEGER PRIMARY KEY, VotedItem INTEGER, UserId INTEGER, FOREIGN KEY(`VotedItem`) REFERENCES item(ItemId), FOREIGN KEY(`UserId`) REFERENCES user(Id))");
    }
    public function addVotingChoice($vote)
    {
        $stmh = $this->db->query('SELECT ItemId FROM item where ItemName = :itemName');
        $stmh->bindParam(':itemName', $vote['votedItem']);
        $stmh->execute();
        $stmh->setFetchMode(\PDO::FETCH_ASSOC);

        if ($row = $stmh->fetch()) {
            $itId = $row['ItemId'];
        }
        //Insert
        $stmh = $this->db->prepare("insert into votes (VotingId, VotedItem, UserId) values (:votingId, :votedItem, :userId)");
        $stmh->bindParam(':votingId', $vote['votingId'];
        $stmh->bindParam(':votedItem', $itId;
        $stmh->bindParam(':userId', $vote['userId'];
        $stmh->execute();

        $stmh = $this->db->prepare("UPDATE user SET HasVoted = :hasVoted WHERE id = :userId");
        $stmh->bindParam(':hasVoted', 1);
        $stmh->bindParam(':id', $vote->getUserId());
        $stmh->execute();
    }

    public function getAllUsers()
    {
        $userlist = array();
        $result = $this->db->query('SELECT * FROM user');
        foreach($result as $row) {
            $user = array(
                "id"=> $row['Id'],
                "name"=> $row['UserName'],
                "hasVoted"=> $row['HasVoted']
            );
            $userlist.push($user);
        }
        return json_encode($userlist);
    }

    public function getAllItems()
    {
        $itemlist = array();
        $result = $this->db->query('SELECT * FROM item');
        foreach($result as $row) {
            $item = array(
                "itemId"=> $row['ItemId'],
                "itemName"=> $row['ItemName']
            );
            $itemlist.push($item);
        }
        return json_encode($itemlist);
    }

    public function getVotingChoiceByUserId($userId)
    {
        $stmh = $this->db->prepare("SELECT * from votes JOIN item ON ItemId=VotedItem JOIN user ON Id=UserId WHERE UserId = :userId");
        $sid = intval($userId);

        $stmh->bindParam(':userId', $sid);
        $stmh->execute();
        $stmh->setFetchMode(\PDO::FETCH_ASSOC);

        if ($row = $stmh->fetch()) {
            $vote = array(
                "votingId"=> $row['VotingId'];
                "votedItemId"=> $row['VotedItem'],
                "votedItemName"=> $row['ItemName'],
                "userId"=> $row['UserId'],
                "hasVoted"=> $row['HasVoted']
            );
            $vote->setVotingId($row['VotingId']);
            $vote->setItemId($row['VotedItem']);
            $vote->setItemName($row['ItemName']);
            $vote->setUserId($row['UserId']);
            $vote->setHasVoted($row['HasVoted']);
            return json_encode($vote);
        } else {
            return array();
        }
    }

    public function getVotingResults()
    {
        $votelist = array();
        $stmh = $this->db->query("SELECT * from votes JOIN item ON ItemId=VotedItem");
        foreach($result as $row) {
            $vote = array(
                "votingId"=> $row['VotingId'],
                "votedItemId"=> $row['VotedItem'],
                "votedItemName"=> $row['ItemName']
            );
            $votelist.push($vote);
        }
        return json_encode($votelist);
    }
}