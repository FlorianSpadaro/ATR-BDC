<!doctype html>

<html>
	<head>
		<title>Page Title</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="initial-scale=1.0">
        <!--<link rel='stylesheet' href='style.css' />-->
		<style>
			#dateAcquis{
				white-space:nowrap;
			}
			body
			{
				font-family:calibri;
				font-size:10pt;
			}
			#contour
			{
				background-color:#A2F8AA;
				width:17.8cm;
				height:9.3cm;
				padding-top: 0.5cm;
			}
			#entete
			{

				width:17.8cm;
				height:2.3cm;
			}
			#centre
			{

				width:17.8cm;
				height:4.2cm;
			}
			#bas
			{

				width:17.8cm;
				height:2.3cm;
			}
			table
			{
				margin-left:0.7cm;
				margin-right:0.7cm;
			}
			.col_1
			{
				width:1.8cm;
			}
			.col_2
			{
				max-width:3.7cm;
				white-space:nowrap;
			}
			.col_3
			{
				
			}
			.col_4
			{
				
			}
			#titre
			{
				font-weight:bold;
				text-align:center;
			}
			#tablecentre
			{
				 border-collapse: collapse; 
				font-size:8pt;
				text-align: center;
			}
			#tablecentre tr td
			{
				border:solid 1px black;

			}
			.t2_col1
			{
				min-width:4.7cm;
				max-width:4.7cm;
				height: 0.5cm;
			}
			.t2_col2
			{
				width:2.1cm;
			}
			.t2_col3
			{
				width:2.5cm;
			}
			.t2_col4
			{
				width:2.8cm;
			}
			.t2_col5
			{
				width:4cm;
			}
			.t3_col1
			{
				width: 6.9cm;
			}
			.t3_col2
			{
				width:5.4cm;
			}
			.t3_col3
			{
				width:2.5cm;
			}
		</style>
	</head>

	<body>
        <div id="contour">
            <div id="entete">
                <div id="titre">TITRE D'HABILTATION</div>
                <div>
                    <table>
                        <tr>
                            <td id="nom" class="col_1">Nom :</td>
                            <td id="nom_caff" class="col_2"><?php echo strtoupper($_POST["nom"]) ?></td>
                            <td id="empl" class="col_3">Employeur :</td>
                            <td id="atr" class="col_4">Ambition Telecom & Réseaux</td>
                        </tr>
                        <tr>
                            <td id="prenom" class="col_1">Prénom :</td>
                            <td id="prenom_caff" class="col_2"><?php echo ucfirst(strtolower($_POST["prenom"])) ?></td>
                            <td id="affect" class="col_3">Affectation:</td>
                            <td id="dir" class="col_4">Direction de production AT&R</td>
                        </tr>
                        <tr>
                            <td id="fonction" class="col_1">Fonction:</td>
                            <td id="fonction_name" class="col_2"><?php echo $_POST["fonction"] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="centre">
                <table id="tablecentre">
                    <tr>
                        
                    </tr>
                        <td rowspan="2" class="t2_col1">Personnel</td>
                        <td rowspan="2" class="t2_col2">Symbole d'habilitation</td>
                        <td colspan="3">Champ d'application</td>
                      
                    <tr>
                        <td  class="t2_col3">Domaine de tension</td>
                        <td class="t2_col4">Ouvrage Concernés</td>
                        <td>Indication supplémentaires</td>
         
                    </tr>
                    <tr>
                        <td class="t2_col1">Non électricien habilité</td>
                        <td class="t2_col2">H0-B0</td>
                        <td class="t2_col3">BT</td>
                        <td class="t2_col4">Chantiers Telecom et Elec</td>
                        <td>Autorisé à intervenir au voisinage pour les relecés nécessaires aux études d'extension réseau</td>
                    </tr>
                    <tr>
                        <td class="t2_col1">Exécutant électricien</td>
                        <td class="t2_col2"></td>
                        <td class="t2_col3"></td>
                        <td class="t2_col4"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="t2_col1">Chargéde travaux ou d'interventions</td>
                        <td class="t2_col2"></td>
                        <td class="t2_col3"></td>
                        <td class="t2_col4"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="t2_col1">Chargé de consignation</td>
                        <td class="t2_col2"></td>
                        <td class="t2_col3"></td>
                        <td class="t2_col4"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="t2_col1">Habilité spéciaux</td>
                        <td class="t2_col2"></td>
                        <td class="t2_col3"></td>
                        <td class="t2_col4"></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div id="bas">
            <table>
                <tr>
                    <td class="t3_col1">Le titulaire</td>
                    <td class="t3_col2">Pour l'employeur</td>
                    <td class="t3_col3" id="dateAcquis">Date: <?php echo substr($_POST["date"], 8, 2)."/".substr($_POST["date"], 5, 2)."/".substr($_POST["date"], 0, 4) ?></td>
                </tr>
                <tr>
                    <td class="t3_col1">Signature:</td>
                    <td class="t3_col2">Nom et Prénom : BOUR Firmin</td>
                    <td class="t3_col3">Validité:3ans</td>
                </tr>
                <tr>
                    <td class="t3_col1"></td>
                    <td class="t3_col2">Fonction:PDG</td>
                </tr>
                <tr>
                    <td class="t3_col1"></td>
                    <td class="t3_col2">Signature:</td>
                </tr>
            </table>
            </div>
        </div>
	</body>
</html>