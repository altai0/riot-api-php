<?php 
/*
altai0g
API

-Summoner Data
-Last Match 
-League Details
-Champion Mastery

*/
class riotApi {

	public $api = "YOUR-TOKEN";
	public $summonerAccountId;

	public function callRiotApi($url){

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$headers = [
			'Origin: https://developer.riotgames.com',
			'Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8',
			'X-Riot-Token: '.$this->api,
			'Accept-Language: tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7',
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36',
		];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$server_output = curl_exec($ch);
		curl_close ($ch);
		$data = json_decode($server_output);
		return $data;

	}


	public function getId($platform,$summonerName){

		$url = "https://{$platform}.api.riotgames.com/lol/summoner/v4/summoners/by-name/$summonerName";

		$data=$this->callRiotApi($url);

		return $data;

	}
	public function getLeague($platform,$summonerId){

		$url = "https://$platform.api.riotgames.com/lol/league/v4/entries/by-summoner/$summonerId";

		$data=$this->callRiotApi($url);

		return $data;

	}

	public function getMatch($platform,$accountId){

		$url = "https://$platform.api.riotgames.com/lol/match/v4/matchlists/by-account/$accountId";

		$data=$this->callRiotApi($url);
		
		return $data;
	}
	public function getLastMatch($platform,$matchId){

		$url = "https://$platform.api.riotgames.com/lol/match/v4/matches/$matchId";

		$data=$this->callRiotApi($url);
		
		return $data;

	}
	public function summonerChamp($platform,$summonerId){

		$url= "https://$platform.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/$summonerId";

		$data=$this->callRiotApi($url);
		
		return $data;
	}
	public function getMatchReturn($platform,$matchId,$summonerId){

        //Match Id check
		$match = $this->getLastMatch($platform,$matchId);
		$err = $match->status;
		if ($err->status_code==404) {
			$err404 = "NoData";
			return $err404;
		}else if($err->status_code==403){
			$err404 = "NoData";
			return $err404;
		}
        //Stats url inside
		$matchDy = $match->participantIdentities;
        //Summoner search
		$i = 0;


		foreach ($matchDy as $key) {
			
			$i++;
			if ($player->accountId === $summonerId) {
				break;
			}
			$player = $key->player;
			$keys = $i;
			
			
		}

		$matchDy = $match->participants;
		$particiId = $keys -1;

        //Summoner find and minus 1
		$result_match = $matchDy[$particiId];
        //All match details
		$stats_match = $result_match->stats;

        //Play champion Id
		$champId = $result_match->championId;

		return ['statsMatch' => $stats_match,'championId' => $champId];
	}


}

?>
