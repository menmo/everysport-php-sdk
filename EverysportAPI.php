<?php
/**
 * PHP SDK to Everysport.com API
 *
 * Preconditions is a valid apikey and that cURL is installed.
 *
 * Created by Fredrik, 2013-02-25
 */
class EverysportAPI {

    /* Default endpoint using the latest version of everysport api. Using trailing slash. */
    private $endpoint = "http://api.everysport.com/v1/";
    private $apikey;
    private $debug = false;


    public function __construct($apikey, $endpoint = null) {

        /*if(empty($apikey)) {
            throw new Exception("Please provide an apikey.");
        }*/

        $this->apikey = $apikey;

        if(!empty($endpoint)) {
            $this->endpoint = $endpoint;
        }

    }

    public function doGETRequest($path, $params = array()) {
        if(empty($this->apikey)) {
            throw new Exception("Please provide an apikey.");
        }


        /* Set all query params */
        $query = "";
        foreach($params as $key => $value) {
            $query = $query."&".$key."=".$value ;
        }

        $url = $this->endpoint . $path . "?apikey=" . $this->apikey . $query;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $resp = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($resp);

        if(!$data) {
            if(json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Parsing error in response from Everysport API with url: ' . $url . ' message: ' . get_json_error_message(), 0, new Exception($resp));
            } else {
                throw new Exception('Empty response from Everysport API with url: ' . $url);
            }
        }

        return $data;
    }

    /**
     * Get a list of events.
     *
     * @param array $params
     * @return response format according to everysport api documentation
     * @throws Exception
     */
    public function listEvents($params = array()) {

        $path = "events";

        return $this->doGETRequest($path, $params);
    }


    /**
     * Get a list of events for specified league.
     *
     * @param $leagueId
     * @param array $params
     * @return response format according to everysport api documentation
     * @throws Exception
     */
    public function listLeagueEvents($leagueId, $params = array()) {

        if(empty($leagueId))
            throw new Exception("league id is required");

        $path = "leagues/".$leagueId."/events";

        return $this->doGETRequest($path, $params);
    }

    /**
     * Get current standing for specified league
     *
     * @param $leagueId
     * @param array $params
     * @return response format according to everysport api documentation
     * @throws Exception
     */
    public function listLeagueStandings($leagueId, $params = array()) {

        if(empty($leagueId))
            throw new Exception("league id is required");

        $path = "leagues/".$leagueId."/standings";

        return $this->doGETRequest($path, $params);
    }

    /**
     * Get sports
     *
     * @return response format according to everysport api documentation
     * @throws Exception
     */
    public function listSports() {

        $path = "sports";

        return $this->doGETRequest($path);
    }

    /**
     * Get leagues
     *
     * @return response format according to everysport api documentation
     *      * @param array $params
     * @throws Exception
     */
    public function listLeagues($params = array()) {

        $path = "leagues";

        return $this->doGETRequest($path, $params);
    }
}
