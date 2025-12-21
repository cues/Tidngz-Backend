<?php



class AddSearch extends Db{
    public function add_search($user_id, $search_item, $search_item_id){
        $date = date("Y-m-d H:i:s");

        try {
            // Use backticks for column names to avoid conflicts with reserved words (e.g. USER, COUNT)
            $this->query("SELECT * FROM `Search_History` WHERE `USER` = ? AND `SEARCH_ITEM` = ? AND `SEARCH_ITEM_ID` = ? LIMIT 1");
            $this->bind(1, $user_id);
            $this->bind(2, $search_item);
            $this->bind(3, $search_item_id);
            $existing = $this->single();

            if(!empty($existing) && isset($existing['ID'])){
                $this->query("UPDATE `Search_History` SET `USER` = ?, `SEARCH_ITEM` = ?, `SEARCH_ITEM_ID` = ?, `DATE` = ? , `COUNT` = ? WHERE `ID` = ?");
                // bind values for COUNT and ID first is fine; we'll bind all positions below
                $this->bind(5, $existing['COUNT'] + 1);
                $this->bind(6, $existing['ID']);

            } else {
                $this->query("INSERT INTO `Search_History` (`USER`, `SEARCH_ITEM`, `SEARCH_ITEM_ID`, `DATE`) VALUES (?,?,?,?)");
            }

            $this->bind(1, $user_id);
            $this->bind(2, $search_item);
            $this->bind(3, $search_item_id);
            $this->bind(4, $date);
            $this->execute();

            $search = New Search_History();
            $data = $search->get_search($user_id);

            $this->closeConnection();
            return $data;
        } catch (Exception $e) {
            // Log the PDO exception so the server error can be diagnosed (will appear in PHP error log)
            error_log("AddSearch::add_search error: " . $e->getMessage());
            // Ensure connection closed
            $this->closeConnection();
            return 0;
        }
    }
}