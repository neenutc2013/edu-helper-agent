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

    // Agent logic
    private function edu_helper_agent($message, $history) {
        $topics = ['solar system', 'fractions', 'water cycle'];
        $msg = strtolower($message);

        foreach ($topics as $topic) {
            if (strpos($msg, $topic) !== false) {
                $reply = "Hello! Here's a quick explanation about " . ucfirst($topic) . ". Keep learning! 😊";
                // Limit to 60 words
                return implode(' ', array_slice(explode(' ', $reply), 0, 60));
            }
        }

        return "I can only help with Solar System, Fractions, or Water Cycle for now 😊";
    }
}
