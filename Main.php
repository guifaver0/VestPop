<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Carregando PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function sendEmail()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        $cpf = $input['cpf'];
        $telefone = $input['telefone'];
        $emailCliente = $input['email'];
        $cart = $input['cart'];

        // Processa os produtos no carrinho
        $productsDetails = "";
        foreach ($cart as $item) {
            $productsDetails .= "{$item['product']} - Tamanho: {$item['size']} - Cor: {$item['color']} - Preço: R$ {$item['price']}\n";
        }

        // Corpo do e-mail
        $emailBody = "Produtos comprados:\n{$productsDetails}\n\n";
        $emailBody .= "Dados do Cliente:\n";
        $emailBody .= "CPF: {$cpf}\n";
        $emailBody .= "Telefone: {$telefone}\n";
        $emailBody .= "E-mail: {$emailCliente}\n";

        // Configuração do PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.mail.yahoo.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vestpop@yahoo.com';
            $mail->Password = 'trmikxbfdvrvtwdy';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('vestpop@yahoo.com', 'VESTPOP');
            $mail->addAddress('vestpop@yahoo.com');

            $mail->isHTML(false);
            $mail->Subject = 'Nova Compra - VESTPOP';
            $mail->Body = $emailBody;

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'E-mail enviado com sucesso!']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => "Erro ao enviar e-mail: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido']);
    }
}

