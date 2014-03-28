<?php // Nugraha, Thu Apr 22 11:09:40 WIT 2004

class PgTrans
{

    var $pSQL;
    var $PgConn;
    var $ErrMsg;
    
    function addSQL($stmt)
    {
        if (is_array($this->pSQL)) {
            $this->pSQL[count($this->pSQL)] = $stmt;
        } else {
            $this->pSQL[0] = $stmt;
        }
    }
    
    function execute()
    {
        if (!is_array($this->pSQL)) {
            $this->ErrMsg = "Layanan medis belum di input";
            return false;
        }
        pg_query($this->PgConn, "BEGIN");
        foreach($this->pSQL as $s) {
            @$pgStat = pg_query($this->PgConn, $s);
            if ($pgStat == false) {
                $this->ErrMsg = pg_last_error($this->PgConn);
                break;
            }
        }
        if ($pgStat == false) {
            pg_query($this->PgConn, "ROLLBACK");
        } else {
            pg_query($this->PgConn, "COMMIT");
        }
        return $pgStat;
    }
    
    function showSQL()
    {
        if (!is_array($this->pSQL)) {
            $this->ErrMsg = "Layanan medis belum di input";
            return false;
        }
        echo "BEGIN<br><br>\n";
        foreach($this->pSQL as $s) {
            echo "$s<BR><br>\n";
        }
        echo "COMMIT<br><br>\n";
    }

}

?>