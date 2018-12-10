<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 20/07/2018
 * Time: 15:26
 */
namespace model;
class ScheduleSendMail
{
    public function __construct()
    {
        // register schedule every 5 minutes
        if (! wp_next_scheduled ( 'gc_schedule_send_mail' )) {
            wp_schedule_event(time(), 'five_minutes', 'gc_schedule_send_mail');
        }
    }


    /**
     * This method is run 5 minutes to send giftcards that has scheduled send date
     */
    public static function ScheduleSendMail() {
        $time_sql          = current_time( 'mysql' );
        $current_date_time = new \DateTime( $time_sql );
        $args = array(
            'post_type' => 'shop_giftcard',
            'meta_query' => array(
                array(
                    'key' => 'gc_is_sent',
                    'value' => 0,
                ),
                array(
                    'key' => 'gc_status',
                    'value' => 1
                )
            )
        );
        $gcs = get_posts($args);
        foreach ($gcs as $gc){
            $scheduled_time = new \DateTime(get_post_meta($gc->ID, 'gc_scheduled_send_time',true));

            if ( $scheduled_time <= $current_date_time){
                // sent
                $giftCard = get_post($gc->ID,ARRAY_A);
                $code             = $giftCard['post_title'];
                $giftcardInstance = new \model\Magenest_Giftcard( $code );
                $giftcardInstance->send( $giftCard['ID'] );
                update_post_meta( $gc->ID, 'gc_is_sent', '1' );
            }
        }
    }

    public static function remove_schedule(){
            wp_clear_scheduled_hook( 'gc_schedule_send_mail' );
    }
}