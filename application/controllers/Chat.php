<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session'); // for conversation history
    }

    // Load chat page
    public function index() {
        $this->load->view('chat_view');
    }

    // Handle user messages
    public function send_message() {
        $user_message = trim($this->input->post('message'));

        // Retrieve previous conversation
        $history = $this->session->userdata('chat_history') ?? [];

        // Get agent reply
        $reply = $this->edu_helper_agent($user_message, $history);

        // Save conversation
        $history[] = ['user' => $user_message, 'agent' => $reply];
        $this->session->set_userdata('chat_history', $history);

        echo json_encode(['reply' => $reply]);
    }


    private function edu_helper_agent($message, $history) {

    $topics = ['solar system', 'fractions', 'water cycle'];
    $msg = strtolower($message);
    $matched = false;

    foreach ($topics as $topic) {
        if (strpos($msg, $topic) !== false) {
            $matched = $topic;
            break;
        }
    }

    if (!$matched) {
        return "I can help only with Solar System, Fractions, or Water Cycle 😊";
    }

    $prompt = "Explain {$matched} simply for a school student. Limit to 60 words.";

    $data = [
        "model" => "phi", // SMALL MODEL
        "prompt" => $prompt,
        "stream" => false
    ];

    // print_r($data);exit;

    $ch = curl_init("http://localhost:11434/api/generate");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (!isset($result['response'])) {
        return "Sorry, I couldn’t respond right now.";
    }

    return implode(' ', array_slice(explode(' ', trim($result['response'])), 0, 60));
}



}
