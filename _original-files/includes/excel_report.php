<?php        require_once(''.$_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
                    require_once(''.$_SERVER['DOCUMENT_ROOT'].'/wp-includes/template-loader.php');

                    global $wp_query, $wpdb, $bd, $current_user;
                    get_currentuserinfo();
                    $userRole = ( $current_user->data->wp_capabilities );
                    $role = $userRole;
                    //echo $role;
                    $zip =  get_option( 'bd_siteZipcode' );
                    $wpdb->blogid = $bd->centralBid;
                    $wpdb->set_prefix( $wpdb->base_prefix );
                    $settings = get_option( 'bd_settings' );

                    $temp = explode( ',',$zip );
                    $zipcode_val = array();
                    for ( $k=0;$k<count($temp);$k++ ) {
                                        array_push( $zipcode_val, trim($temp[$k]) );
                    }
		
                    if ( array_key_exists('catname', $_GET) ) {
                                        $theCatId = get_term_by( 'slug', $_GET['catname'], 'business_type' );
                                        $theCatId = $theCatId->term_id;
                                        if ($theCatId!=''){
                                                            $filters[] = array('key' => 'bd_cat', 'value' => $theCatId, 'type'=>'numeric', 'compare' => 'IN');
                                        }
                    }

                    if ( array_key_exists( 'cityname', $_GET ) && $_GET['cityname']!='' ) {
                                        $filters[] = array( 'key' => 'bd_city', 'value' => $_GET['cityname'] );
                    }

                    if ( $role == "business" || ( is_super_admin() ) ) {
                    } else {
                                        $filters[] = array('key' => 'bd_closing_day1');
                                        $filters[] = array('key' => 'bd_closing_day2');
                    }

                    $filters[] = array( 'key' => 'bd_zipcode', 'value' => $zipcode_val, 'compare' => 'IN' );
                    
                  if ( array_key_exists( 'bsearch', $_GET ) && $_GET['bsearch']!='Search by name or ZIP' && $_GET['bsearch']!='' ) {
                                      
                   $search_term = $_GET['bsearch'];
                                        $args = array(
                                                            'post_type'=>'business',
                                                            'post_status'=>'publish',
                                                           'orderby' => 'title',
                                                            'order' => 'ASC',
                                                            'posts_per_page' => -1,
                                                            'meta_query' =>  array( //'relation' => 'AND',
                                                                                                                array(
                                                                                                                    'value' => $search_term,
                                                                                                                    'compare' => 'LIKE'
                                                                                                                    ),
                                                                                                    //array( 'key' => 'bd_zipcode', 'value' => $zipcode_val, 'compare' => 'IN' )
                                                                                )
                                                             );
                 
		}else{
                                        $args = array(
                                                            'post_type'=>'business',
                                                            'post_status'=>'publish',
                                                            'meta_query' =>   $filters,
                                                            'posts_per_page' => -1,
                                                            'orderby' => 'title',
                                                            'order' => 'ASC'
                                        );			
                    }

                    //echo "admin";
            //       if ( array_key_exists( 'bsearch', $_GET ) && $_GET['bsearch']!='Search by name or ZIP' && $_GET['bsearch']!='' ) {
                                        if ( array_key_exists( 'statusid', $_GET ) && $_GET['statusid']!='' ) {
                                                            $args['meta_query'][] = array(
                                                            'value'   => $_GET['statusid'],
                                                            'compare' => 'IN'
                                                            );
                                        }
                   // }
              
                                        
                    $the_query = new WP_Query( $args );
                     
                    $unewline = "\r\n";
                    if ( strstr( strtolower( $_SERVER["HTTP_USER_AGENT"] ), 'win') ) {
                    $unewline = "\r\n";
                    } else if ( strstr(strtolower( $_SERVER["HTTP_USER_AGENT"] ), 'mac' ) ) {
                    $unewline = "\r";
                    } else {
                    $unewline = "\n";
                    }
                    $column_name = array( 'Sl no.', 'Business Name', 'Contact Person', 'Email ID', 'Phone Number', 'City', 'Zip Code', 'Web url', 'Today Status', 'Tomorrow Status', 'Details' );
                    foreach( $column_name as $caname ) {
                    $csv_output .= trim( $caname )."\t";
                    }
                    echo $csv_output.$unewline;
                    
                    $i=1;
                    if ( $the_query->post_count > 0 ) {

                                        foreach($the_query->posts as $post){
                                                            $pid = $post->ID;
                                                            $businesname =  get_post_meta($pid, 'bd_bname', true);  
                                                            $city =  get_post_meta($pid, 'bd_city', true);  
                                                            $contact   =  get_post_meta($pid, 'bd_contact', true);    
                                                            $day1 = get_post_meta( $pid, 'bd_closing_day1', true);
                                                            $day2 = get_post_meta( $pid, 'bd_closing_day2', true);
                                                            $email  =  get_post_meta($pid, 'bd_user_mail', true);  
                                                            $phone    =  get_post_meta($pid, 'bd_phone', true);  
                                                            $zipcode  =  get_post_meta($pid, 'bd_zipcode', true);  
                                                            $weburl   =  get_post_meta($pid, 'bd_website', true); 
                                                            $todayStatus = get_post_meta( $pid, 'bd_day1_status', true);
                                                            $tmrstatus = get_post_meta($pid, 'bd_day2_status',true);
                                                           
                                                            // Today Status
                                                            if ($todayStatus == "normal_hours"){
                                                                                $todayStatus = 'Normal Hours';
                                                            }
                                                            else if ($todayStatus == ""){
                                                                                $todayStatus = 'Normal Hours';
                                                            }
                                                            if ($todayStatus == "early_dismisal"){
                                                                                $todayStatus = 'Early Dismissal';
                                                            }
                                                            if ($todayStatus == "closed"){
                                                                                $todayStatus = 'Closed';
                                                            }
                                                            
                                                            // Tomorrow Status
                                                            if ($tmrstatus == "normal_hours"){
                                                                                $tmrstatus = 'Normal Hours';
                                                            }
                                                            else if ($tmrstatus == ""){
                                                                                $tmrstatus = 'Normal Hours';
                                                            }
                                                             if ($tmrstatus == "early_dismisal"){
                                                                                $tmrstatus = 'Early Dismissal';
                                                            }
                                                             if ($tmrstatus == "closed"){
                                                                                $tmrstatus = 'Closed';
                                                            }
                                                            
                                        $csv_output1 .= $i."\t".strip_tags($businesname)."\t".strip_tags($contact)."\t".strip_tags($email)."\t".strip_tags($phone)."\t".strip_tags($city)."\t".strip_tags($zipcode)."\t".strip_tags($weburl)."\t".strip_tags($todayStatus)."\t".strip_tags($tmrstatus)."\t".strip_tags($history['details']).$unewline;
                                        $i++;
                                        }
                    }

                    $file= "Closings";
                    $filename = $file."_".date("Y-m-d_H-i",time());
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-disposition: xls" . date("Y-m-d") . ".xls");
                    header( "Content-disposition: filename=".$filename.".xls");
                    echo $csv_output1;
                    die();