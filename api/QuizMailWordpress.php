<?php
//define('SHORTINIT', true);
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

$quiz_mail = new QuizMailWordpress();

add_action('wp_ajax_send_company_quiz_mail', [$quiz_mail, 'send_mail']);
add_action('wp_ajax_nopriv_send_company_quiz_mail', [$quiz_mail, 'send_mail'] );

class QuizMailWordpress
{
    private $data;
    private $json;
    private $from = "postmaster@your-company.com";
    private $to_admin = "admin@your-company.com";
    public $to_user;
    private $to_developer = "dev@your-company.com";
    private $subject = [
      'admin' => "Новая заявка с Квиза",
      'user' => "Спасибо, что выбрали нас"
    ];
    private $company_info = [
        'name' => 'Your Company',
        'email' => 'info@your-company.com',
        'phone' => '+7 (000) 000-00-00',
        'website_url' =>  'https://your-company.com'
    ];
    public $error_message;

    public function __construct()
    {
        remove_all_filters('wp_mail_from');
        remove_all_filters('wp_mail_from_name');
        add_filter('wp_mail_from_name', [$this, 'set_from_name']);
    }

    public function send_mail()
    {
        $this->data = file_get_contents("php://input");
        $this->json = json_decode($this->data, true);
        $this->to_user = $this->json['userFullinfo']['email'];

        $mail = wp_mail($this->to_admin, $this->subject['admin'], $this->converted_message( $this->set_message_admin($this->json), 'utf-8' ), $this->converted_headers( $this->set_headers(), 'utf-8' ) );
        $mail .= wp_mail($this->to_user, $this->subject['user'], $this->converted_message( $this->set_message_user($this->json), 'utf-8' ), $this->converted_headers( $this->set_headers(), 'utf-8' ) );

        if (!$mail) {
            $this->error_message = error_get_last()['message'];
            echo $this->error_message;
        } else {
            echo "success";
        }

        wp_die();
    }

    public function set_message_admin($data)
    {
        return '
            <table style="width: 602px; border: 0; border-collapse: collapse; font-family: Montserrat, Roboto, Helvetica, Arial, sans-serif;">
            ' . $this->html_message_header() . ' ' .
            $this->html_message_footer() . ' ' .
            $this->html_message_body_admin($data)
            . '
            </table>
        ';
    }

    public function set_message_user($data)
    {
        return '
            <table style="width: 602px; border: 0; border-collapse: collapse; font-family: Montserrat, Roboto, Helvetica, Arial, sans-serif;">
            ' . $this->html_message_header() . ' ' .
            $this->html_message_footer() . ' ' .
            $this->html_message_body_user($data)
            . '
            </table>
        ';
    }

    public function set_from_name()
    {
        return $this->company_info["name"];
    }

    public function set_headers()
    {
        return "From: $this->from" . PHP_EOL .
            "MIME-Version: 1.0" . PHP_EOL .
            "Content-type: text/html; charset=utf-8" . PHP_EOL;
    }

    public function converted_message($msg, $encode_type)
    {
        return mb_convert_encoding($msg, $encode_type, mb_detect_encoding($msg));
    }

    public function converted_headers($headers, $encode_type)
    {
        return mb_convert_encoding($headers, $encode_type, mb_detect_encoding($headers));
    }

    public function html_message_header()
    {
        return '
          <thead style="color: #ffffff; border-bottom: 2px solid #0F4C81;" bgcolor="#0F1C2D">
            <tr>
                <th style="padding: 25px 0 25px 10px; text-align: left; font-size: 22px;" width="300" >' . $this->company_info["name"] . '</th>
                <td style="padding: 25px 10px 25px 0; text-align: right; font-size: 14px; color: #ffffff" width="300">' . $this->company_info["email"] . '<br>' . $this->company_info["phone"] . '</td>
            </tr>
          </thead>
        ';
    }

    public function html_message_footer()
    {
        return '
          <tfoot width="100%" bgcolor="#0F1C2D">
            <tr>
              <td style="padding: 20px 0 20px 10px; text-align: left;" width="300">
                <a href="' . $this->company_info["website_url"] . '" style="color: #ffffff;">Перейти на сайт</a>
              </td>
              <td style="padding: 10px 0;"></td>
            </tr>
          </tfoot>
        ';
    }

    public function html_message_body_admin($data)
    {
        return '
              <tbody style="width: 100%; color: #0F1C2D;" bgcolor="#ffffff">
                <tr>
                  <td width="300" bgcolor="#E4EDF2" style="padding: 35px 0 35px 10px; color: #0F1C2D; font-size: 32px;">' . $this->subject['admin'] . '</td>
                  <td bgcolor="#E4EDF2"></td>
                </tr>
                <tr>
                    <th style="text-align: left; color: #D5242C; padding: 15px 0 10px 10px; font-size: 20px;" width="300">Пользователь:</th>
                    <th width="300"></th>
                </tr>
                ' .
            $this->html_user_info($data)
            . '
                <tr>
                    <th style="padding: 10px 0 10px 10px; text-align: left; color: #D5242C; font-size: 20px;" width="300">Данные:</th>
                    <th width="300"></th>
                </tr>
                ' .
            $this->html_user_data($data)
            . '
              </tbody>
        ';
    }

    public function html_message_body_user($data)
    {
        return '
              <tbody style="width: 100%; color: #0F1C2D;" bgcolor="#ffffff">
                <tr>
                  <td width="300" bgcolor="#E4EDF2" style="padding: 35px 0 35px 10px; color: #0F1C2D; font-size: 32px;">' . $this->subject["user"] . ', ' . $data["userFullinfo"]["name"] . '</td>
                  <td bgcolor="#E4EDF2"></td>
                </tr>
                <tr>
                    <th style="text-align: left; color: #D5242C; padding: 15px 0 10px 10px; font-size: 20px;" width="300">Вы сделали правильный выбор!</th>
                    <th width="300"></th>
                </tr>
                <tr>
                    <td width="300" style="padding: 10px 10px;">Менеджер уже обрабатывает заявку и свяжется с Вами в ближайшее время</td>
                    <td width="300"></td>
                </tr>
              </tbody>
        ';
    }

    public function html_user_info($data)
    {
        $content = '';
        foreach ($data["userFullinfo"] as $key => $value) {
            $content .= '<tr>';
            $content .= '<td width="300" style="padding: 10px 10px;">' . $key . '</td>';
            $content .= '<td width="300" style="padding: 10px 10px;">' . $value . '</td>';
            $content .= '</tr>';
        }

        return $content;
    }

    public function html_user_data($data)
    {
        $content = '';
        foreach ($data["quizData"] as $key => $value) {
            $content .= '<tr>';
            foreach ($value as $item_key => $item_value) {
                if (is_array($item_value)) {
                    $content .= '<tr>';
                    $content .= '<td width="300" style="padding: 10px 10px;">' . $item_key . '</td>';
                    $content .= '<td width="300" style="padding: 10px 10px;">' . implode(",", $item_value) . '</td>';
                    $content .= '</tr>';
                } else {
                    $content .= '<tr>';
                    $content .= '<td width="300" style="padding: 10px 10px;">' . $item_key . '</td>';
                    $content .= '<td width="300" style="padding: 10px 10px;">' . $item_value . '</td>';
                    $content .= '</tr>';
                }
            }
            $content .= '</tr>';
        }

        return $content;
    }

}
