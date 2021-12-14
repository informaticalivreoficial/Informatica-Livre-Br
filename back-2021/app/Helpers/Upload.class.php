<?php

/**
 * Upload.class [ HELPER ]
 * Reponsável por executar upload de imagens, arquivos e mídias no sistema!
 * 
 * @copyright (c) 2017, Renato Montanari
 */
class Upload {

    private $File;
    private $Name;
    private $Send;

    /** IMAGE UPLOAD */
    private $Width;
    private $Image;
    private $Marcadagua;

    /** RESULTSET */
    private $Result;
    private $Error;

    /** DIRETÓTIOS */
    private $Folder;
    private static $BaseDir;
    private static $BaseDir1;

    /**
     * Verifica e cria o diretório padrão de uploads no sistema!<br>
     * <b>../uploads/</b>
     */
    function __construct($BaseDir = null) {
        self::$BaseDir = ( (string) $BaseDir ? $BaseDir : '../uploads/');
        self::$BaseDir1 = ( (string) $BaseDir ? $BaseDir : $_SERVER['DOCUMENT_ROOT'].'../uploads/');
        if (!file_exists(self::$BaseDir) && !is_dir(self::$BaseDir)):
            mkdir(self::$BaseDir, 0777);
        endif;
    }

    /**
     * <b>Enviar Imagem:</b> Basta envelopar um $_FILES de uma imagem e caso queira um nome e uma largura personalizada.
     * Caso não informe a largura será 1024!
     * @param FILES $Image = Enviar envelope de $_FILES (JPG ou PNG)
     * @param STRING $Name = Nome da imagems ( ou do artigo )
     * @param INT $Width = Largura da imagem ( 1024 padrão )
     * @param STRING $Folder = Pasta personalizada
     */
    public function Image(array $Image, $Name = null, $Width = null, $Folder = null) {
        $this->File = $Image;
        $this->Name = ( (string) $Name ? $Name : substr($Image['name'], 0, strrpos($Image['name'], '.')) );
        $this->Width = ( (int) $Width ? $Width : 1024 );
        $this->Folder = ( (string) $Folder ? $Folder : 'imagens' );

        $this->CheckFolder($this->Folder);
        $this->setFileName();
        $this->UploadImage();
    }
    
    public function ImageComMarca(array $Image, $Name = null, $Width = null, $Folder = null, $Marca = false) {
        $this->File = $Image;
        $this->Name = ( (string) $Name ? $Name : substr($Image['name'], 0, strrpos($Image['name'], '.')) );
        $this->Width = ( (int) $Width ? $Width : 1024 );
        $this->Folder = ( (string) $Folder ? $Folder : 'imagens' );
        $this->Marcadagua = $Marca;
        $this->CheckFolder($this->Folder);
        $this->setFileName();
        $this->UploadImageMarcaDagua();
    }
    
    public function ImageSetPasta(array $Image, $Name = null, $Width = null, $Folder = null) {
        $this->File = $Image;
        $this->Name = ( (string) $Name ? $Name : substr($Image['name'], 0, strrpos($Image['name'], '.')) );
        $this->Width = ( (int) $Width ? $Width : 1024 );
        $this->Folder = ( (string) $Folder ? $Folder : 'imagens' );

        $this->CheckFolderPasta($this->Folder);
        $this->setFileName();
        $this->UploadImagePasta();
    }
    
    public function ImageSemData(array $Image, $Name = null, $Width = null, $Folder = null) {
        $this->File = $Image;
        $this->Name = ( (string) $Name ? $Name : substr($Image['name'], 0, strrpos($Image['name'], '.')) );
        $this->Width = ( (int) $Width ? $Width : 1024 );
        $this->Folder = ( (string) $Folder ? $Folder : 'imagens' );

        $this->CheckFolderSemData($this->Folder);
        $this->setFileName();
        $this->UploadImage();
    }

    /**
     * <b>Enviar Arquivo:</b> Basta envelopar um $_FILES de um arquivo e caso queira um nome e um tamanho personalizado.
     * Caso não informe o tamanho será 2mb!
     * @param FILES $File = Enviar envelope de $_FILES (PDF ou DOCX)
     * @param STRING $Name = Nome do arquivo ( ou do artigo )
     * @param STRING $Folder = Pasta personalizada
     * @param STRING $MaxFileSize = Tamanho máximo do arquivo (2mb)
     */
    public function File(array $File, $Name = null, $Folder = null, $MaxFileSize = null) {
        $this->File = $File;
        $this->Name = ( (string) $Name ? $Name : substr($File['name'], 0, strrpos($File['name'], '.')) );
        $this->Folder = ( (string) $Folder ? $Folder : 'files' );
        $MaxFileSize = ( (int) $MaxFileSize ? $MaxFileSize : 2 );

        $FileAccept = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',// docx
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',// xlsx
            'application/vnd.ms-excel',// xls
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',// pptx
            'application/pdf'// pdf
        ];

        if ($this->File['size'] > ($MaxFileSize * (1024 * 1024))):
            $this->Result = false;
            $this->Error = "Arquivo muito grande, tamanho máximo permitido de {$MaxFileSize}mb";
        elseif (!in_array($this->File['type'], $FileAccept)):
            $this->Result = false;
            $this->Error = 'Tipo de arquivo não suportado. Envie .PDF, XLS, PPTX, XLSX ou .DOCX!';
        else:
            $this->CheckFolder($this->Folder);
            $this->setFileName();
            $this->MoveFile();
        endif;
    }

    /**
     * <b>Enviar Mífia:</b> Basta envelopar um $_FILES de uma mídia e caso queira um nome e um tamanho personalizado.
     * Caso não informe o tamanho será 40mb!
     * @param FILES $Media = Enviar envelope de $_FILES (MP3 ou MP4)
     * @param STRING $Name = Nome do arquivo ( ou do artigo )
     * @param STRING $Folder = Pasta personalizada
     * @param STRING $MaxFileSize = Tamanho máximo do arquivo (40mb)
     */
    public function Media(array $Media, $Name = null, $Folder = null, $MaxFileSize = null) {
        $this->File = $Media;
        $this->Name = ( (string) $Name ? $Name : substr($Media['name'], 0, strrpos($Media['name'], '.')) );
        $this->Folder = ( (string) $Folder ? $Folder : 'medias' );
        $MaxFileSize = ( (int) $MaxFileSize ? $MaxFileSize : 40 );

        $FileAccept = [
            'audio/mp3',
            'video/mp4'
        ];

        if ($this->File['size'] > ($MaxFileSize * (1024 * 1024))):
            $this->Result = false;
            $this->Error = "Arquivo muito grande, tamanho máximo permitido de {$MaxFileSize}mb";
        elseif (!in_array($this->File['type'], $FileAccept)):
            $this->Result = false;
            $this->Error = 'Tipo de arquivo não suportado. Envie audio MP3 ou vídeo MP4!';
        else:
            $this->CheckFolder($this->Folder);
            $this->setFileName();
            $this->MoveFile();
        endif;
    }

    /**
     * <b>Verificar Upload:</b> Executando um getResult é possível verificar se o Upload foi executado ou não. Retorna
     * uma string com o caminho e nome do arquivo ou FALSE.
     * @return STRING  = Caminho e Nome do arquivo ou False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com um code, um title, um erro e um tipo.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Verifica e cria os diretórios com base em tipo de arquivo, ano e mês!
    private function CheckFolder($Folder) {
        list($y, $m) = explode('/', date('Y/m'));
        $this->CreateFolder("{$Folder}");
        $this->CreateFolder("{$Folder}/{$y}");
        $this->CreateFolder("{$Folder}/{$y}/{$m}/");
        $this->Send = "{$Folder}/{$y}/{$m}/";
    }
    
    //Verifica e cria os diretórios com base em tipo de arquivo, ano e mês!
    private function CheckFolderPasta($Folder) {
        list($y, $m) = explode('/', date('Y/m'));
        $this->CreateFolderPasta("{$Folder}");
        $this->CreateFolderPasta("{$Folder}/{$y}");
        $this->CreateFolderPasta("{$Folder}/{$y}/{$m}/");
        $this->Send = "{$Folder}/{$y}/{$m}/";
    }
    
    //Verifica e cria os diretórios com base em tipo de arquivo!
    private function CheckFolderSemData($Folder) {
        $this->CreateFolder("{$Folder}");        
        $this->Send = "{$Folder}/";
    }
    

    //Verifica e cria o diretório base!
    private function CreateFolder($Folder) {
        if (!file_exists(self::$BaseDir . $Folder) && !is_dir(self::$BaseDir . $Folder)):
            mkdir(self::$BaseDir . $Folder, 0777);
        endif;
    }
    
    //Verifica e cria o diretório base!
    private function CreateFolderPasta($Folder) {
        if (!file_exists(self::$BaseDir1 . $Folder) && !is_dir(self::$BaseDir1 . $Folder)):
            mkdir(self::$BaseDir1 . $Folder, 0777);
        endif;
    }

    //Verifica e monta o nome dos arquivos tratando a string!
    private function setFileName() {
        $FileName = Check::Name($this->Name) . strrchr($this->File['name'], '.');
        if (file_exists(self::$BaseDir . $this->Send . $FileName)):
            $FileName = Check::Name($this->Name) . '-' . time() . strrchr($this->File['name'], '.');
        endif;
        $this->Name = $FileName;
    }

    //Realiza o upload de imagens redimensionando a mesma!
    private function UploadImage() {

        switch ($this->File['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->Image = imagecreatefromjpeg($this->File['tmp_name']);
            break;
            case 'image/png':
            case 'image/x-png':
                $this->Image = imagecreatefrompng($this->File['tmp_name']);
            break;
            case 'image/gif':
                $this->Image = imagecreatefromgif($this->File['tmp_name']);
            break;
        endswitch;

        if (!$this->Image):
            $this->Result = false;
            $this->Error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG!';
        else:
            $x = imagesx($this->Image);
            $y = imagesy($this->Image);
            $ImageX = ( $this->Width < $x ? $this->Width : $x );
            $ImageH = ($ImageX * $y) / $x;

            $NewImage = imagecreatetruecolor($ImageX, $ImageH);
            imagealphablending($NewImage, false);
            imagesavealpha($NewImage, true);
            imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $ImageX, $ImageH, $x, $y);

            switch ($this->File['type']):
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewImage, self::$BaseDir . $this->Send . $this->Name);
                break;
                case 'image/png':
                case 'image/x-png':
                    imagepng($NewImage, self::$BaseDir . $this->Send . $this->Name);
                break;
                case 'image/gif':
                    imagegif($NewImage, self::$BaseDir . $this->Send . $this->Name);
                break;
            endswitch;

            if (!$NewImage):
                $this->Result = false;
                $this->Error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG!';
            else:
                $this->Result = $this->Send . $this->Name;
                $this->Error = null;
            endif;

            imagedestroy($this->Image);
            imagedestroy($NewImage);
        endif;
    }
    
     //Realiza o upload de imagens redimensionando a mesma & com Marca D´agua!
    private function UploadImageMarcaDagua() { 
        
        switch ($this->File['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->Image = imagecreatefromjpeg($this->File['tmp_name']);                
                break;
            case 'image/png':
            case 'image/x-png':
                $this->Image = imagecreatefrompng($this->File['tmp_name']);                
                break;
        endswitch;

        if (!$this->Image):
            $this->Result = false;
            $this->Error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG!';
        else:
            $x = imagesx($this->Image);
            $y = imagesy($this->Image);
            $ImageX = ( $this->Width < $x ? $this->Width : $x );
            $ImageH = ($ImageX * $y) / $x;
            
            //VALIDA A LOGO DA MARCA DAGUA
            if($this->Marcadagua == true):
                //dados da mascara [caminho do arquivo que serve de mascara]
                $BaseMarca =  self::$BaseDir.MARCADAGUA;
                if(exif_imagetype($BaseMarca) == IMAGETYPE_GIF):
                    $Logomarca = imagecreatefromgif($BaseMarca);
                elseif(exif_imagetype($BaseMarca) == IMAGETYPE_PNG):
                    $Logomarca = imagecreatefrompng($BaseMarca);
                elseif(exif_imagetype($BaseMarca) == IMAGETYPE_JPEG):
                    $Logomarca = imagecreatefromjpeg($BaseMarca);
                endif;
                
                //config da marca dagua
                $Largura_logo = imagesx($Logomarca);
                $Altura_logo = imagesy($Logomarca);
                $xlogo = imagesx($this->Image) - $Largura_logo - 100;
                $ylogo = imagesy($this->Image) - $Altura_logo - 80;            
                imagecopy($this->Image, $Logomarca, $xlogo, $ylogo, 0, 0, $Largura_logo, $Altura_logo);                
            endif;            

            $NewImage = imagecreatetruecolor($ImageX, $ImageH);
            imagealphablending($NewImage, false);
            imagesavealpha($NewImage, true);
            imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $ImageX, $ImageH, $x, $y);
            
            
            switch ($this->File['type']):
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewImage, self::$BaseDir . $this->Send . $this->Name);                    
                    break;
                case 'image/png':
                case 'image/x-png':
                    imagepng($NewImage, self::$BaseDir . $this->Send . $this->Name);
                    break;
            endswitch;

            if (!$NewImage):
                $this->Result = false;
                $this->Error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG!';
            else:
                $this->Result = $this->Send . $this->Name;
                $this->Error = null;
            endif;
            
            imagedestroy($this->Image);
            imagedestroy($NewImage);
            if($this->Marcadagua == true):
                imagedestroy($Logomarca);
            endif;            
        endif;
    }
    
    //Realiza o upload de imagens redimensionando a mesma!
    private function UploadImagePasta() {

        switch ($this->File['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->Image = imagecreatefromjpeg($this->File['tmp_name']);
                break;
            case 'image/png':
            case 'image/x-png':
                $this->Image = imagecreatefrompng($this->File['tmp_name']);
                break;
        endswitch;

        if (!$this->Image):
            $this->Result = false;
            $this->Error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG!';
        else:
            $x = imagesx($this->Image);
            $y = imagesy($this->Image);
            $ImageX = ( $this->Width < $x ? $this->Width : $x );
            $ImageH = ($ImageX * $y) / $x;

            $NewImage = imagecreatetruecolor($ImageX, $ImageH);
            imagealphablending($NewImage, false);
            imagesavealpha($NewImage, true);
            imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $ImageX, $ImageH, $x, $y);

            switch ($this->File['type']):
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewImage, self::$BaseDir1 . $this->Send . $this->Name);
                    break;
                case 'image/png':
                case 'image/x-png':
                    imagepng($NewImage, self::$BaseDir1 . $this->Send . $this->Name);
                    break;
            endswitch;

            if (!$NewImage):
                $this->Result = false;
                $this->Error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG!';
            else:
                $this->Result = $this->Send . $this->Name;
                $this->Error = null;
            endif;

            imagedestroy($this->Image);
            imagedestroy($NewImage);
        endif;
    }

    //Envia arquivos e mídias
    private function MoveFile() {
        if (move_uploaded_file($this->File['tmp_name'], self::$BaseDir . $this->Send . $this->Name)):
            $this->Result = $this->Send . $this->Name;
            $this->Error = null;
        else:
            $this->Result = false;
            $this->Error = 'Erro ao mover o arquivo. Favor tente mais tarde!';
        endif;
    }

}
