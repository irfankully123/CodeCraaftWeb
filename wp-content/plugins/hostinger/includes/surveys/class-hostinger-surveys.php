<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hostinger_Surveys {
	const SUBMIT_SURVEY = '/v3/wordpress/survey/store';
	const GET_SURVEY = '/v3/wordpress/survey/get';
	const CLIENT_SURVEY_ELIGIBILITY = '/v3/wordpress/survey/client-eligible';
	const CLIENT_SURVEY_IDENTIFIER = 'customer_satisfaction_score';
	const REQUIRED_SURVEY_ITEMS = [
		[
			'question_slug' => 'location',
			'answer'        => 'wordpress_cms'
		]
	];
	private $config_handler;
	private $settings;
	private $client;
	private $error_handler;
	private $helper;
	private $survey_questions;

	public function __construct() {
		$this->settings         = new Hostinger_Settings();
		$this->helper           = new Hostinger_Helper();
		$this->config_handler   = new Hostinger_Config();
		$this->error_handler    = new Hostinger_Errors();
		$this->survey_questions = new Hostinger_Surveys_Questions();
		$this->client           = new Hostinger_Requests_Client( $this->config_handler->get_config_value( 'base_rest_api', HOSTINGER_REST_URI ), [
			'X-Hpanel-Order-Token' => $this->helper::get_api_token(),
		] );
	}

	public function is_survey_enabled(): bool {
		return ! $this->settings->get_setting( 'feedback_survey_completed' ) && $this->settings->get_setting( 'content_published' ) && $this->is_client_eligible();
	}

	public function is_client_eligible(): bool {
		$response = $this->client->get( self::CLIENT_SURVEY_ELIGIBILITY, [
			'identifier' => self::CLIENT_SURVEY_IDENTIFIER,
		] );

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $response ) || $response_code !== 200 ) {
			return false;
		}

		$response_data = json_decode( $response_body );

		if ( isset( $response_data->data ) && $response_data->data === true ) {
			return true;
		}

		return false;
	}

	private function get_survey_questions(): array {
		$response = $this->client->get( self::GET_SURVEY, [
			'identifier' => self::CLIENT_SURVEY_IDENTIFIER,
		] );

		if ( is_wp_error( $response ) ) {
			return [];
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		if ( $response_code !== 200 || empty( $response_body ) ) {
			return [];
		}

		$response_data = json_decode( $response_body, true );

		if ( isset( $response_data['data']['questions'] ) && is_array( $response_data['data']['questions'] ) ) {
			return $response_data['data']['questions'];
		}

		return [];
	}

	public function submit_survey_answers( array $answers ): void {

		$data = [
			'identifier' => self::CLIENT_SURVEY_IDENTIFIER,
			'answers'    => self::REQUIRED_SURVEY_ITEMS

		];

		foreach ( $answers as $answer_slug => $answer ) {
			$answer            = [
				'question_slug' => $answer_slug,
				'answer'        => $answer
			];
			$data['answers'][] = $answer;
		}

		$response = $this->client->post( self::SUBMIT_SURVEY, $data );

		if ( is_wp_error( $response ) ) {
			error_log( print_r( $response, true ) );
			wp_send_json_error( __('Survey failed', 'hostinger') );
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		$response_data = json_decode( $response_body, true );

		if ( $response_code == 200 && $response_data['success'] ) {
			$this->settings->update_setting( 'feedback_survey_completed', true );
			wp_send_json( __('Survey completed', 'hostinger') );
		}

	}

	public function get_required_survey_questions(): array {
		$all_questions = $this->get_survey_questions();

		return $this->filter_required_questions( $all_questions );
	}

	private function filter_required_questions( array $all_questions ): array {
		$questions_with_required_rule = [];

		foreach ( $all_questions as $question ) {
			if (
				isset( $question['slug'], $question['rules'] ) &&
				in_array( 'required', $question['rules'] )
			) {
				$questions_with_required_rule[] = [
					'slug'  => $question['slug'],
					'rules' => $question['rules']
				];
			}
		}


		return $questions_with_required_rule;
	}

	public function generate_json( $required_questions ) {
		$jsonStructure = [
			"pages"               => [],
			"showQuestionNumbers" => "off",
			"showTOC"             => false,
			"pageNextText"        => __( 'Next', 'hostinger' ),
			"pagePrevText"        => __( 'Previous', 'hostinger' ),
			"completeText"        => __( 'Submit', 'hostinger' ),
			"completedHtml"       => __( 'Thank you for completing the survey !', 'hostinger' ),
			"requiredText"        => '*',
		];

		foreach ( $required_questions as $question ) {

			$element = [
				"type"              => $this->survey_questions->map_survey_questions( $question['slug'] )['type'],
				"name"              => $question['slug'],
				"title"             => $this->survey_questions->map_survey_questions( $question['slug'] )['question'],
				"isRequired"        => true,
				"requiredErrorText" => __( 'Response required.', 'hostinger' ),
			];

			if( $question['slug'] == 'comment' ) {
				$element['maxLength'] = 250;
			}

			if ( isset( $question['rules'] ) ) {

				$betweenRule = $this->getBetweenRuleValues( $question['rules'] );
				if ( $betweenRule ) {
					$element["rateMin"] = $betweenRule[0];
					$element["rateMax"] = $betweenRule[1];
					$element["minRateDescription"] = __( 'Poor', 'hostinger' );
					$element["maxRateDescription"] = __( 'Excellent', 'hostinger' );
				}
			}

			$question_data = [
				"name"     => $question['slug'],
				"elements" => [ $element ]
			];

			$jsonStructure["pages"][] = $question_data;
		}

		return json_encode( $jsonStructure );
	}

	public function getBetweenRuleValues( array $rules ): array {
		foreach ( $rules as $rule ) {
			if ( strpos( $rule, 'between:' ) === 0 ) {
				$betweenValues = explode( ',', substr( $rule, 8 ) );
				if ( count( $betweenValues ) === 2 ) {
					return $betweenValues;
				}
			}
		}

		return [];
	}

}

$surveys = new Hostinger_Surveys();