<?php
/**
 * Class SampleTest
 *
 * @package Mae_Ticket
 */


/**
 * Sample test case.
 */
class PostmetaTest extends WP_UnitTestCase {

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		require dirname( dirname( __FILE__ ) ) . '/model/class.maetic_postmeta.php';
		var_dump(shell_exec('wp db import ./bin/wordpress.sql'));
		var_dump(shell_exec('wp core update-db'));
	}

	/**
	 * A single example test.
	 */
	public function test_get_ordered_date() {
		var_dump(get_users());
//		$result =MaeTick_Postmeta::get_ordered_date(13);
		$post = get_post(1);
		var_dump("hello");
		$title = $post->post_title;

		$this->assertTrue( $title );
	}
}
