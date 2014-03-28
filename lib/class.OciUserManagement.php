<?php

/*
 * Class UserManagement
 * Azwari Nugraha, 16 January 2007
 * Last Updated: 19 January 2007
 */

final class OciUserManagement implements IfUserManagement {
    
    private $oci_conn;
    private $tbl_user;
    private $tbl_user_fields;
    private $str_user_fields;
    private $is_authenticated;
    private $message;
    
    private $user_id;
    private $user_name;
    private $user_created;
    private $user_updated;
    private $user_last_login;
    private $sql;
    
    private $root;
    
    public function __construct( $oci_conn = null ) {
        $this->is_authenticated = FALSE;
        ini_set("track_errors", 1);
        $this->set_connection( $oci_conn );
    }
    
    public function get_message() { return $this->message; }
    public function get_user_id() { return $this->user_id; }
    public function get_user_name() { return $this->user_name; }
    public function get_user_created() { return $this->user_created; }
    public function get_user_updated() { return $this->user_updated; }
    public function get_user_last_login() { return $this->user_last_login; }
    public function user_is_admin() { return $this->user_id == $this->root; }
    public function is_authenticated() { return $this->is_authenticated; }
    public function get_user_sql() { return $this->sql; }

    public function set_connection( $oci_conn ) {
        $this->oci_conn = $oci_conn;
        if ($this->oci_conn == FALSE) {
            $err = oci_error();
            $this->error($err);
            return;
        }
    }
    
    public function set_user_table( $tbl_name, $tbl_fields ) {
        $this->tbl_user = $tbl_name;
        $this->tbl_user_fields = $tbl_fields;
        $this->str_user_fields = "{$tbl_fields['id']}, {$tbl_fields['password']}";
        if (strlen($tbl_fields['name']) > 0) $this->str_user_fields .= ", {$tbl_fields['name']}";
        if (strlen($tbl_fields['date_created']) > 0) $this->str_user_fields .= ", {$tbl_fields['date_created']}";
        if (strlen($tbl_fields['date_updated']) > 0) $this->str_user_fields .= ", {$tbl_fields['date_updated']}";
        if (strlen($tbl_fields['date_login']) > 0) $this->str_user_fields .= ", {$tbl_fields['date_login']}";
        $sql  = "SELECT ";
        $sql .= $this->str_user_fields;
        $this->sql  = $sql;
        $this->sql .= " FROM {$tbl_name}";
        $sql .= " FROM {$tbl_name} WHERE 0 = 1";
        $st = oci_parse($this->oci_conn, $sql);
        if (@!oci_execute($st, OCI_DEFAULT)) {
            $err = oci_error( $st );
            $this->error($err);
            return;
        } else {
            oci_free_statement( $st );
        }
    }
    
    public function set_admin( $admin ) {
        $this->root = $admin;
    }
    
    public function authenticate( $user, $password ) {
        $this->is_authenticated = FALSE;
        if (strlen($user) == 0 || strlen($password) == 0) {
            $this->message = "User / password can not be empty";
            return FALSE;
        } else {
            $st = oci_parse($this->oci_conn,
                "SELECT {$this->str_user_fields} ".
                "FROM {$this->tbl_user} ".
                "WHERE {$this->tbl_user_fields['id']} = '{$user}'");
            oci_execute($st, OCI_DEFAULT);
            if ($dt = oci_fetch_array($st, OCI_ASSOC)) {
                if ($dt[$this->tbl_user_fields["password"]] == md5($password)) {
                    $this->is_authenticated = TRUE;
                    $this->message = "";
                    $this->user_id = $dt[$this->tbl_user_fields["id"]];
                    $this->user_name = $dt[$this->tbl_user_fields["name"]];
                    $this->user_created = $dt[$this->tbl_user_fields["date_created"]];
                    $this->user_updated = $dt[$this->tbl_user_fields["date_updated"]];
                    $this->user_last_login = $dt[$this->tbl_user_fields["date_login"]];
                    if (array_key_exists("date_login", $this->tbl_user_fields)) {
                        $st1 = oci_parse($this->oci_conn,
                            "UPDATE {$this->tbl_user} SET ".
                            "{$this->tbl_user_fields['date_login']} = SYSDATE ".
                            "WHERE {$this->tbl_user_fields['id']} = '{$user}'");
                        oci_execute($st1, OCI_COMMIT_ON_SUCCESS);
                        oci_free_statement($st1);
                    }
                } else {
                    $this->message = "Invalid password";
                }
            } else {
                $this->message = "User '{$user}' is not registered";
            }
            oci_free_statement($st);
            return $this->is_authenticated;
        }
    }
    
    public function logoff() {
        $this->is_authenticated = false;
        $this->user_name = "";
    }
    
    public function user_exists( $user ) {
        $st = oci_parse($this->oci_conn,
            "SELECT {$this->str_user_fields} ".
            "FROM {$this->tbl_user} ".
            "WHERE {$this->tbl_user_fields['id']} = '{$user}'");
        oci_execute($st, OCI_DEFAULT);
        if ($dt = oci_fetch_array($st, OCI_NUM)) {
            $ret = TRUE;
        } else {
            $ret = FALSE;
        }
        oci_free_statement($st);
        return $ret;
    }
    
    public function remove_user( $user ) {
        if (!$this->is_authenticated()) {
            $this->message = "Login required";
            return FALSE;
        } elseif (!$this->user_is_admin()) {
            $this->message = "Insufficient privilege";
            return FALSE;
        } elseif (!$this->user_exists($user)) {
            $this->message = "User '{$user}' does not exists";
            return FALSE;
        } elseif ($user == $this->root) {
            $this->message = "Can not remove admin user";
            return FALSE;
        } else {
            $st = oci_parse($this->oci_conn,
                "DELETE FROM {$this->tbl_user} ".
                "WHERE {$this->tbl_user_fields['id']} = '{$user}'");
            oci_execute($st, OCI_COMMIT_ON_SUCCESS);
            oci_free_statement($st);
            $this->message = "User '{$user}' has been removed";
            return TRUE;
        }
    }
    
    public function update_user( $user, $password, $confirm_password, $tags = null ) {
        if (!$this->is_authenticated()) {
            $this->message = "Login required";
            return FALSE;
        } elseif (!$this->user_is_admin()) {
            $this->message = "Insufficient privilege";
            return FALSE;
        } elseif (!$this->user_exists($user)) {
            $this->message = "User '{$user}' does not exists";
            return FALSE;
        } elseif ($password != $confirm_password) {
            $this->message = "Confirm password does not match";
            return FALSE;
        } else {
            if (strlen($password) > 0) {
                $st = oci_parse($this->oci_conn,
                    "UPDATE {$this->tbl_user} SET ".
                    "{$this->tbl_user_fields['password']} = '" . md5($password) . "' ".
                    "WHERE {$this->tbl_user_fields['id']} = '{$user}'");
                oci_execute($st, OCI_COMMIT_ON_SUCCESS);
                oci_free_statement($st);
            }
            if (is_array($tags)) {
                foreach ($tags as $k => $v) {
                    $st = oci_parse($this->oci_conn,
                        "UPDATE {$this->tbl_user} SET ".
                        $this->tbl_user_fields[$k] . " = '{$v}' ".
                        "WHERE {$this->tbl_user_fields['id']} = '{$user}'");
                    oci_execute($st, OCI_COMMIT_ON_SUCCESS);
                    oci_free_statement($st);
                }
            }
            $this->message = "User '{$user}' has been updated";
            return TRUE;
        }
    }
    
    public function add_user( $user, $password, $confirm_password, $tags = null ) {
        if (!$this->is_authenticated()) {
            $this->message = "Login required";
            return FALSE;
        } elseif (!$this->user_is_admin()) {
            $this->message = "Insufficient privilege";
            return FALSE;
        } elseif ($this->user_exists($user)) {
            $this->message = "User '{$user}' already exists";
            return FALSE;
        } elseif ($password != $confirm_password) {
            $this->message = "Confirm password does not match";
            return FALSE;
        } elseif (strlen($password) == 0) {
            $this->message = "The password can not be empty";
            return FALSE;
        } else {
            $st = oci_parse($this->oci_conn,
                "INSERT INTO {$this->tbl_user} ".
                "({$this->tbl_user_fields['id']}, {$this->tbl_user_fields['password']}) ".
                "VALUES ('{$user}', '" . md5($password) . "')");
            oci_execute($st, OCI_COMMIT_ON_SUCCESS);
            oci_free_statement($st);
            if (is_array($tags)) {
                foreach ($tags as $k => $v) {
                    if ($k == "id" || $k == "password") continue;
                    $st = oci_parse($this->oci_conn,
                        "UPDATE {$this->tbl_user} SET ".
                        $this->tbl_user_fields[$k] . " = ".
                        "'" . $tags[$k] . "' ".
                        "WHERE {$this->tbl_user_fields['id']} = '{$user}'");
                    oci_execute($st, OCI_COMMIT_ON_SUCCESS);
                    oci_free_statement($st);
                }
            }
            if (array_key_exists("date_created", $this->tbl_user_fields)) {
                $st = oci_parse($this->oci_conn,
                    "UPDATE {$this->tbl_user} SET ".
                    "{$this->tbl_user_fields['date_created']} = SYSDATE ".
                    "WHERE {$this->tbl_user_fields['id']} = '{$user}'");
                oci_execute($st, OCI_COMMIT_ON_SUCCESS);
                oci_free_statement($st);
            }
            if (array_key_exists("date_updated", $this->tbl_user_fields)) {
                $st = oci_parse($this->oci_conn,
                    "UPDATE {$this->tbl_user} SET ".
                    "{$this->tbl_user_fields['date_updated']} = SYSDATE ".
                    "WHERE {$this->tbl_user_fields['id']} = '{$user}'");
                oci_execute($st, OCI_COMMIT_ON_SUCCESS);
                oci_free_statement($st);
            }
            $this->message = "User '{$user}' has been added";
            return TRUE;
        }
    }
    
    public function change_password( $old_password, $new_password, $confirm_password ) {
        if (!$this->is_authenticated()) {
            $this->message = "Login required";
            return FALSE;
        } elseif ($new_password != $confirm_password) {
            $this->message = "Confirm password does not match";
            return FALSE;
        } elseif (strlen($new_password) == 0) {
            $this->message = "The password can not be empty";
            return FALSE;
        } else {
            $st = oci_parse($this->oci_conn,
                "SELECT {$this->tbl_user_fields['password']} ".
                "FROM {$this->tbl_user} ".
                "WHERE {$this->tbl_user_fields['id']} = '{$this->user_id}'");
            oci_execute($st, OCI_DEFAULT);
            if ($dt = oci_fetch_array($st, OCI_NUM)) $md5passwd = $dt[0];
            oci_free_statement($st);
            if ($md5passwd != md5($old_password)) {
                $this->message = "Invalid old password";
                return FALSE;
            } else {
                $st = oci_parse($this->oci_conn,
                    "UPDATE {$this->tbl_user} SET ".
                    "{$this->tbl_user_fields['password']} = '" . md5($new_password) . "' ".
                    "WHERE {$this->tbl_user_fields['id']} = '{$this->user_id}'");
                oci_execute($st, OCI_COMMIT_ON_SUCCESS);
                oci_free_statement($st);
                $this->message = "The password for user '{$this->user_id}' has been changed";
                return TRUE;
            }
        }
    }
    
    public function clear_messages() {
        $this->message = "";
    }
    
    private function error( $err ) {
        echo "<b><big>Class UserManagement Error:</big></b>";
        echo "<br><code>{$err['message']}</code>";
    }
    
}

?>