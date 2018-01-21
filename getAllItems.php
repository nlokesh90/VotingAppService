<?php

require_once 'VotingService.php';

$votingService = new VotingService();

return $votingService->getAllItems();

?>