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
        // Dados do cliente enviados via POST
        $cpf = $this->input->post('cpf');
        $telefone = $this->input->post('telefone');
        $emailCliente = $this->input->post('email');
        $cart = $this->input->post('cart'); // Carrinho (produtos selecionados)

        // Detalhes dos produtos
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
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.yahoo.com';  // Provedor de e-mail (exemplo: smtp.gmail.com)
            $mail->SMTPAuth = true;
            $mail->Username = 'vestpop@yahoo.com'; // Seu e-mail
            $mail->Password = 'Gui@140704';            // Sua senha
            $mail->SMTPSecure = 'tls';               // Criptografia (TLS/SSL)
            $mail->Port = 587;                       // Porta do servidor SMTP

            // Remetente e destinatário
            $mail->setFrom('seuemail@dominio.com', 'VESTPOP');
            $mail->addAddress('vestpop@yahoo.com');  // E-mail da loja

            // Conteúdo do e-mail
            $mail->isHTML(false);  // Definir o formato do e-mail como texto simples
            $mail->Subject = 'Nova Compra - VESTPOP';
            $mail->Body = $emailBody;

            // Enviar o e-mail
            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'E-mail enviado com sucesso!']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => "Erro ao enviar e-mail: {$mail->ErrorInfo}"]);
        }
    }
}
