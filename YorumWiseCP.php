<?php 
/**
 * YorumWiseCP - Wisecp Tüm Yorumlar Özel Sayfa Eklentisi
 *
 * Yazar: Ömer ATABER - OmerAti JRodix.Com Internet Hizmetleri
 * Versiyon: 1.0.0
 * Tarih: 19.09.2024
 * Web: https://www.jrodix.com
 *
 */
class YorumWiseCP extends AddonModule {
    public $version = "1.0";
    
    function __construct(){
        $this->_name = __CLASS__;
        parent::__construct();
    }
    
    public function fields(){
        $settings = isset($this->config['settings']) ? $this->config['settings'] : [];
        return [
            'YorumWiseCP_yol'          => [
                'wrap_width'        => 100,
                'name'              => $this->lang["YorumWiseCP_yol_name"],
                'description'       => $this->lang["YorumWiseCP_yol_description"],
                'type'              => "text",
                'value'             => isset($settings["YorumWiseCP_yol"]) ? $settings["YorumWiseCP_yol"] : "",
                'placeholder'       => "Tema Yolunuz",
            ],
        ];
    }
    public function deactivate(){

    $settings = isset($this->config['settings']) ? $this->config['settings'] : [];
    $yol = isset($settings['YorumWiseCP_yol']) ? $settings['YorumWiseCP_yol'] : '';


    if (!empty($yol) && is_dir($yol)) {
        $filePath = rtrim($yol, '/') . '/musteri-gorusleri.php'; 

        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                $this->error = "Dosya silinirken bir hata oluştu.";
                return false;
            }
        }
    }

    return true;
}

public function save_fields($fields = [])
{

    $settings = isset($this->config['settings']) ? $this->config['settings'] : [];
    $yol = isset($fields['YorumWiseCP_yol']) ? $fields['YorumWiseCP_yol'] : '';


    if (empty($yol)) {
        $this->error = "Tema Yolu boş olamaz.";
        return false;
    }


    if (!is_dir($yol)) {
        $this->error = "Belirtilen yol geçerli bir dizin değil.";
        return false;
    }


    $filePath = rtrim($yol, '/') . '/musteri-gorusleri.php';


    if (file_exists($filePath)) {
        if (!unlink($filePath)) {
            $this->error = "Eski dosya silinemedi.";
            return false;
        }
    }
    $fileContent = "<?php\n";
    $fileContent .= "defined('CORE_FOLDER') OR exit('You can not get in here!');\n";
    $fileContent .= "\$query = WDB::select('id, full_name, company_name, email, ctime');\n";
    $fileContent .= "\$query->from('customer_feedbacks');\n";
    $fileContent .= "\$query->where('status', '=', 'approved');\n";
    $fileContent .= "\$query = \$query->build(true);\n";
    $fileContent .= "\$feedbacks = WDB::fetch_assoc();\n";
    $fileContent .= "\$lang_query = WDB::select('owner_id, lang, message');\n";
    $fileContent .= "\$lang_query->from('customer_feedbacks_lang');\n";
    $fileContent .= "\$lang_query->build(true);\n";
    $fileContent .= "\$messages = WDB::fetch_assoc();\n";
    $fileContent .= "\$messages_by_id = [];\n";
    $fileContent .= "foreach (\$messages as \$message) {\n";
    $fileContent .= "    \$messages_by_id[\$message['owner_id']][\$message['lang']] = \$message['message'];\n";
    $fileContent .= "}\n";
    $fileContent .= "?>\n";
    $fileContent .= "<style>\n";
    $fileContent .= "    body {\n";
    $fileContent .= "        font-family: 'Arial', sans-serif;\n";
    $fileContent .= "        background-color: #f4f4f9;\n";
    $fileContent .= "        margin: 0;\n";
    $fileContent .= "        padding: 0;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .customer-reviews {\n";
    $fileContent .= "        padding: 3rem 1rem;\n";
    $fileContent .= "        max-width: 1200px;\n";
    $fileContent .= "        margin: 0 auto;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .customer-reviews h2 {\n";
    $fileContent .= "        text-align: center;\n";
    $fileContent .= "        font-size: 2.5rem;\n";
    $fileContent .= "        margin-bottom: 2rem;\n";
    $fileContent .= "        color: #333;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .review-grid {\n";
    $fileContent .= "        display: grid;\n";
    $fileContent .= "        grid-template-columns: repeat(4, 1fr);\n";
    $fileContent .= "        gap: 2rem;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .review-card {\n";
    $fileContent .= "        background: #ffffff;\n";
    $fileContent .= "        border-radius: 15px;\n";
    $fileContent .= "        box-shadow: 0 10px 20px rgba(0,0,0,0.1);\n";
    $fileContent .= "        padding: 2rem;\n";
    $fileContent .= "        transition: transform 0.3s ease;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .review-card:hover {\n";
    $fileContent .= "        transform: translateY(-10px);\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .review-header {\n";
    $fileContent .= "        display: flex;\n";
    $fileContent .= "        align-items: center;\n";
    $fileContent .= "        margin-bottom: 1.5rem;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .review-logo {\n";
    $fileContent .= "        width: 60px;\n";
    $fileContent .= "        height: 60px;\n";
    $fileContent .= "        border-radius: 50%;\n";
    $fileContent .= "        margin-right: 1rem;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .review-card h4 {\n";
    $fileContent .= "        margin: 0;\n";
    $fileContent .= "        font-size: 1.2rem;\n";
    $fileContent .= "        font-weight: bold;\n";
    $fileContent .= "        color: #333;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .review-card h5 {\n";
    $fileContent .= "        margin: 0;\n";
    $fileContent .= "        font-size: 1rem;\n";
    $fileContent .= "        font-style: italic;\n";
    $fileContent .= "        color: #777;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    .review-card p {\n";
    $fileContent .= "        font-size: 1rem;\n";
    $fileContent .= "        font-style: italic;\n";
    $fileContent .= "        color: #555;\n";
    $fileContent .= "    }\n";
    $fileContent .= "    @media (max-width: 992px) {\n";
    $fileContent .= "        .review-grid {\n";
    $fileContent .= "            grid-template-columns: repeat(2, 1fr);\n";
    $fileContent .= "        }\n";
    $fileContent .= "    }\n";
    $fileContent .= "    @media (max-width: 600px) {\n";
    $fileContent .= "        .review-grid {\n";
    $fileContent .= "            grid-template-columns: 1fr;\n";
    $fileContent .= "        }\n";
    $fileContent .= "    }\n";
    $fileContent .= "</style>\n";
    $fileContent .= "<section class='customer-reviews'>\n";
    $fileContent .= "    <div class='container'>\n";
    $fileContent .= "        <h2>Yorumlar</h2>\n";
    $fileContent .= "        <div class='review-grid' id='reviewGrid'>\n";
    $fileContent .= "            <?php\n";
    $fileContent .= "            if (!empty(\$feedbacks)) {\n";
    $fileContent .= "                foreach (\$feedbacks as \$feedback) {\n";
    $fileContent .= "                    echo '<div class=\"review-card\">';\n";
    $fileContent .= "                    echo '<div class=\"review-header\">';\n";
    $fileContent .= "                    echo '<div>';\n";
    $fileContent .= "                    echo '<h4>' . htmlspecialchars(\$feedback['full_name']) . '</h4>';\n";
    $fileContent .= "                    echo '<h5>' . htmlspecialchars(\$feedback['company_name']) . '</h5>';\n";
    $fileContent .= "                    echo '</div>';\n";
    $fileContent .= "                    echo '</div>';\n";
    $fileContent .= "                    \$message = isset(\$messages_by_id[\$feedback['id']][\$current_lang])\n";
    $fileContent .= "                        ? \$messages_by_id[\$feedback['id']][\$current_lang]\n";
    $fileContent .= "                        : (isset(\$messages_by_id[\$feedback['id']]['en'])\n";
    $fileContent .= "                            ? \$messages_by_id[\$feedback['id']]['en']\n";
    $fileContent .= "                            : 'Mesaj bulunamadi.');\n";
    $fileContent .= "                    echo '<p>\"' . htmlspecialchars(\$message) . '\"</p>';\n";
    $fileContent .= "                    echo '</div>';\n";
    $fileContent .= "                }\n";
    $fileContent .= "            } else {\n";
    $fileContent .= "                echo \"<p>Yorum bulunamadi.</p>\";\n";
    $fileContent .= "            }\n";
    $fileContent .= "            ?>\n";
    $fileContent .= "        </div>\n";
    $fileContent .= "    </div>\n";
    $fileContent .= "</section>\n";

    if (file_put_contents($filePath, $fileContent) === false) {
        $this->error = "Dosya oluşturulurken bir hata oluştu.";
        return false;
    }
    return $fields;
}

    
}
?>
