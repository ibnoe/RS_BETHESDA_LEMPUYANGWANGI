<?php

/*
 * Interface IfUserManagement
 * Azwari Nugraha, 16 January 2007
 * Last Updated: 16 January 2007
 */

interface IfUserManagement {
    
    public function __construct( $conn = null );    
    public function get_message();
    public function get_user_id();
    public function get_user_name();
    public function get_user_created();
    public function get_user_updated();
    public function get_user_last_login();
    public function user_is_admin();
    public function is_authenticated();
    public function get_user_sql();
    public function set_connection( $conn );
    public function set_user_table( $tbl_name, $tbl_fields );    
    public function set_admin( $admin );
    public function authenticate( $user, $password );
    public function user_exists( $user );
    public function remove_user( $user );
    public function update_user( $user, $password, $confirm_password, $tags = null );    
    public function add_user( $user, $password, $confirm_password, $tags = null );
    public function change_password( $old_password, $new_password, $confirm_password );
    
}

?>