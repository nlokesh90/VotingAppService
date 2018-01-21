<?php

require_once 'VotingService.php';

$votingService = new VotingService();

$res = $votingService->getVotingResults();

$votingResults = array();

foreach($res as $key => $value) {
    if($key == 'votedItemName' && $votingResults[$value]){
        $votingResults[$value] += 1;
    } else {
        $votingResults[$value] = 1;
    }
}

arsort($votingResults);

return json_encode($votingResults);

?>