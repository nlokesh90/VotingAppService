<?php

require_once 'VotingService.php';

$vote = json_decode($_POST);
$votingService = new VotingService();

return $votingService->addVotingChoice($vote);

?>