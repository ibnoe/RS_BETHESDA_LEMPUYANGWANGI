<?php // Nugraha, 14/02/2004

Class InsertQuery
{

    var $HttpAction;
    var $TableName;
    var $VarPrefix;
    var $VarTypeIsDate;
    
    var $pNewVar;
    var $pNewVarIdx;
    
    function InsertQuery()
    {
        $this->HttpAction = "POST";
        $this->pNewVarIdx = -1;
    }
    
    function addFieldValue($fname, $fval)
    {
        $this->pNewVarIdx++;
        $this->pNewVar[$this->pNewVarIdx]["k"] = $fname;
        $this->pNewVar[$this->pNewVarIdx]["v"] = $fval;
    }
    
    function build()
    {
        if (strtoupper(trim($this->HttpAction)) == "POST") {
            $F = $_POST;
        } else {
            $F = $_GET;
        }
        
        $n = -1;
        foreach ($F as $key => $val) {
            if (substr($key,0,strlen($this->VarPrefix)) == $this->VarPrefix) {
                $n++;
                $G[$n]["k"] = substr($key,strlen($this->VarPrefix));
                $G[$n]["v"] = "'" . $val . "'";
            }
        }
        
        if (is_array($this->VarTypeIsDate)) {
            foreach ($this->VarTypeIsDate as $val) {
                foreach ($G as $k => $v) {
                    if (substr($v["k"],0,strlen($v["k"])-1) == $val) {
                        unset($G[$k]);
                    }
                }
            }
        }
        
        if (is_array($this->VarTypeIsDate)) {
            foreach ($this->VarTypeIsDate as $val) {
                $n++;
                $G[$n]["k"] = $val;
                $G[$n]["v"] = "'" .
                    $F[$this->VarPrefix . $val . "Y"] . "-" . 
                    $F[$this->VarPrefix . $val . "M"] . "-" .
                    $F[$this->VarPrefix . $val . "D"] . "'";
            }
        }
                
        if (is_array($this->pNewVar)) {
            foreach ($this->pNewVar as $val) {
                $n++;
                $G[$n]["k"] = $val["k"];
                $G[$n]["v"] = $val["v"];
            }
        }
                
        $SQL = "INSERT INTO $this->TableName (";
        $first = true;
        foreach ($G as $key => $val) {
            if ($first) {
                $first = false;
                $SQL .= $val["k"];
            } else {
                $SQL .= ", " . $val["k"];
            }
        }
        $SQL .= ") VALUES (";
        $first = true;
        foreach ($G as $key => $val) {
            if ($first) {
                $first = false;
                $SQL .= $val["v"];
            } else {
                $SQL .= ", " . $val["v"];
            }
        }
        $SQL .= ")";
        
        return $SQL;
        
    }

}

Class UpdateQuery
{

    var $HttpAction;
    var $TableName;
    var $VarPrefix;
    var $VarTypeIsDate;
    
    var $pNewVar;
    var $pNewVarIdx;
    var $pPrimaryKey;
    var $pPrimaryKeyIdx;
    
    function UpdateQuery()
    {
        $this->HttpAction = "POST";
        $this->pNewVarIdx = -1;
        $this->pPrimaryKeyIdx = -1;
    }
    
    function addFieldValue($fname, $fval)
    {
        $this->pNewVarIdx++;
        $this->pNewVar[$this->pNewVarIdx]["k"] = $fname;
        $this->pNewVar[$this->pNewVarIdx]["v"] = $fval;
    }
    
    function addPrimaryKey($fname, $fval)
    {
        $this->pPrimaryKeyIdx++;
        $this->pPrimaryKey[$this->pPrimaryKeyIdx]["k"] = $fname;
        $this->pPrimaryKey[$this->pPrimaryKeyIdx]["v"] = $fval;
    }
    
    function build()
    {
        if ($this->pPrimaryKeyIdx == -1)
            die("UpdateQuery harus menyebutkan minimal satu primary key, ".
                "gunakan method addPrimaryKey(field,value)");
                
        if (strtoupper(trim($this->HttpAction)) == "POST") {
            $F = $_POST;
        } else {
            $F = $_GET;
        }
        
        $n = -1;
        foreach ($F as $key => $val) {
            if (substr($key,0,strlen($this->VarPrefix)) == $this->VarPrefix) {
                $n++;
                $G[$n]["k"] = substr($key,strlen($this->VarPrefix));
                $G[$n]["v"] = "'" . $val . "'";
            }
        }
        
        if (is_array($this->VarTypeIsDate)) {
            foreach ($this->VarTypeIsDate as $val) {
                foreach ($G as $k => $v) {
                    if (substr($v["k"],0,strlen($v["k"])-1) == $val) {
                        unset($G[$k]);
                    }
                }
            }
        }
        
        if (is_array($this->VarTypeIsDate)) {
            foreach ($this->VarTypeIsDate as $val) {
                $n++;
                $G[$n]["k"] = $val;
                $G[$n]["v"] = "'" .
                    $F[$this->VarPrefix . $val . "Y"] . "-" . 
                    $F[$this->VarPrefix . $val . "M"] . "-" .
                    $F[$this->VarPrefix . $val . "D"] . "'";
            }
        }
                
        if (is_array($this->pNewVar)) {
            foreach ($this->pNewVar as $val) {
                $n++;
                $G[$n]["k"] = $val["k"];
                $G[$n]["v"] = $val["v"];
            }
        }
                
        $SQL = "UPDATE $this->TableName SET ";
        $first = true;
        foreach ($G as $key => $val) {
            if ($first) {
                $first = false;
                $SQL .= $val["k"] . " = " . $val["v"];
            } else {
                $SQL .= ", " . $val["k"] . " = " . $val["v"];
            }
        }
        
        $first = true;
        foreach ($this->pPrimaryKey as $val) {
            if($first) {
                $SQL .= " WHERE " . $val["k"] . " = " . $val["v"];
                $first = false;
            } else {
                $SQL .= " AND " . $val["k"] . " = " . $val["v"];
            }
        }
        
        return $SQL;
        
    }

}

?>