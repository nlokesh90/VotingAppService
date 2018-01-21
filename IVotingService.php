<?php

interface INoteRepository
{
    public function getAllUsers();
    public function getAllItems();
    public function addVotingChoice($vote);
    public function getVotingResults();
    public function getVotingChoiceByUserId($userId);
}

?>