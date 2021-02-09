<?php
class MyDictonDay extends Module
{
  
 	public function __construct()
    {
        $this->name = 'mydictonday';
        $this->tab = 'front_office_features';
        $this->version = '0.1.0';
        $this->author = 'Sacha';
        $this->displayName = 'Mon module du dicton et conseil du jour';
        $this->description = ' Avec ce module, vos clients verront le dicton et le conseil du jour JARDITOU';
		$this->bootstrap = true; // Possibilité d'utiliser le Framework CSS Bootstrap depuis la version Prestashop 1.6.
		parent::__construct();
		
    }
  
	public function install()
    {
        parent::install(); // Cette méthode de la classe parente Module permet entre autres choses de créer l'ajout du module dans la table ps_module de Prestashop.
		$this->registerHook('displayLeftColumn'); // Permet d'accrocher le module au hook leftColumn.
        return true;
    }
	
	// Récupération des données de la table de ps_dicton de Prestashop, mise en variables 
	// et assignées à SMARTY.  
	public function assignMyDictonDisplayLeftColumn()
    {
		$dicton = Db::getInstance()->getRow('SELECT * FROM ps_dicton WHERE jour = DAYOFMONTH(CURTIME()) and mois = MONTH(CURTIME())');
		
		// PHP Windows configure la localisation de PHP pour faire afficher une date au format 
		// texte en français 
		setlocale(LC_ALL, 'frc');
		
		$strDate = mb_convert_encoding('%A %d %B %Y','ISO-8859-9','UTF-8');    
    	$daydate = iconv("ISO-8859-9","UTF-8",strftime($strDate ,strtotime(date("d-m-Y"))));
		$daydate = ucfirst($daydate);
		
		$daysaint = $dicton['saint'];
        $daydicton = $dicton['dicton'];
       	$dayconseil = $dicton['conseil'];
	 	
		// On assigne pour SMARTY les différentes valeurs retournées par la requête SQL.
		$this->context->smarty->assign('daydate', $daydate);
        $this->context->smarty->assign('daysaint', $daysaint);
        $this->context->smarty->assign('daydicton', $daydicton);
		$this->context->smarty->assign('dayconseil', $dayconseil);
    }
	
	// Affichage des informations du côté front office, accroché au hook displayLeftColumn.  
	public function hookdisplayLeftColumn($params)
	{
		$this->assignMyDictonDisplayLeftColumn();
        return $this->display(__FILE__, 'displayLeftColumn.tpl');
		
	}	
}
?>