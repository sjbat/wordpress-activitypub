<?php

class Rest_Activitypub_Nodeinfo {
	/**
	 * Register routes
	 */
	public static function register_routes() {
		register_rest_route(
			'activitypub/1.0', '/nodeinfo/discovery', array(
				array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array( 'Rest_Activitypub_Nodeinfo', 'discovery' ),
				),
			)
		);

		register_rest_route(
			'activitypub/1.0', '/nodeinfo', array(
				array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array( 'Rest_Activitypub_Nodeinfo', 'nodeinfo' ),
				),
			)
		);

		register_rest_route(
			'activitypub/1.0', '/nodeinfo2', array(
				array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array( 'Rest_Activitypub_Nodeinfo', 'nodeinfo2' ),
				),
			)
		);
	}

	/**
	 * Render NodeInfo file
	 *
	 * @param  WP_REST_Request   $request
	 * @return WP_REST_Response
	 */
	public static function nodeinfo( $request ) {
		$nodeinfo = array();

		$nodeinfo['version'] = '2.0';
		$nodeinfo['software'] = array(
			'name' => 'wordpress',
			'version' => get_bloginfo( 'version' ),
		);

		$users = count_users();
		$posts = wp_count_posts();
		$comments = wp_count_comments();

		$nodeinfo['usage'] = array(
			'users' => array(
				'total' => (int) $users['total_users'],
			),
			'localPosts' => (int) $posts->publish,
			'localComments' => (int) $comments->approved,
		);

		$nodeinfo['openRegistrations'] = false;
		$nodeinfo['protocols'] = array('activitypub');

		$nodeinfo['services'] = array(
			'inbound' => array(),
			'outbound' => array(),
		);

		$nodeinfo['metadata'] = new stdClass;

		return new WP_REST_Response( $nodeinfo, 200 );
	}

	/**
	 * Render NodeInfo file
	 *
	 * @param  WP_REST_Request   $request
	 * @return WP_REST_Response
	 */
	public static function nodeinfo2( $request ) {
		$nodeinfo = array();

		$nodeinfo['version'] = '1.0';
		$nodeinfo['software'] = array(
			'baseUrl' => home_url( '/' ),
			'name' => get_bloginfo( 'name' ),
			'software' => 'wordpress',
			'version' => get_bloginfo( 'version' ),
		);

		$users = count_users();
		$posts = wp_count_posts();
		$comments = wp_count_comments();

		$nodeinfo['usage'] = array(
			'users' => array(
				'total' => (int) $users['total_users'],
			),
			'localPosts' => (int) $posts->publish,
			'localComments' => (int) $comments->approved,
		);

		$nodeinfo['openRegistrations'] = false;
		$nodeinfo['protocols'] = array('activitypub');

		$nodeinfo['services'] = array(
			'inbound' => array(),
			'outbound' => array(),
		);

		$nodeinfo['metadata'] = new stdClass;

		return new WP_REST_Response( $nodeinfo, 200 );
	}

	/**
	 * Render NodeInfo discovery file
	 *
	 * @param  WP_REST_Request   $request
	 * @return WP_REST_Response
	 */
	public static function discovery( $request ) {
		$discovery = array();
		$discovery['links'] = array(
			array(
				'rel' => 'http://nodeinfo.diaspora.software/ns/schema/2.0',
				'href' => get_rest_url( null, 'activitypub/1.0/nodeinfo' ),
			),
		);

		return new WP_REST_Response( $discovery, 200 );
	}

	/**
	 * Extend NodeInfo data
	 *
	 * @param array $nodeinfo NodeInfo data
	 * @param array           updated data
	 */
	public static function add_nodeinfo_discovery( $nodeinfo, $version ) {
		if ( '2.0' == $version) {
			$nodeinfo['protocols'][] = 'activitypub';
		} else {
			$nodeinfo['protocols']['inbound'][] = 'activitypub';
			$nodeinfo['protocols']['outbound'][] = 'activitypub';
		}
		return $nodeinfo;
	}

	/**
	 * Extend NodeInfo2 data
	 *
	 * @param array $nodeinfo NodeInfo2 data
	 * @param array           updated data
	 */
	public static function add_nodeinfo2_discovery( $nodeinfo ) {
		$nodeinfo['protocols'][] = 'activitypub';
		return $nodeinfo;
	}
}