<?php
	require_once("fonctions.php");
	echo addHabilitationElectrique($_POST["formulaire"]);
	
	/*$formulaire = (object) array();
	$formulaire->brouillon = false;
	$formulaire->q1_ans1 = "gre";
	$formulaire->q1_ans2 = "frz";
	$formulaire->q1_ans3 = "fere";

	$formulaire->q2_ans1 = true;
	$formulaire->q2_ans2 = false;

	$formulaire->q3_ans1 = true;
	$formulaire->q3_ans2 = false;
	$formulaire->q3_ans3 = true;

	$formulaire->q4_ans1 = true;
	$formulaire->q4_ans2 = false;
	$formulaire->q4_ans3 = true;

	$formulaire->q5_ans1 = true;
	$formulaire->q5_ans2 = true;

	$formulaire->q6_ans1 = 'grz';

	$formulaire->q7_ans1 = true;
	$formulaire->q7_ans2 = true;
	
	$formulaire->q8_ans1 = false;
	$formulaire->q8_ans2 = true;

	$formulaire->q9_ans1 = true;
	$formulaire->q9_ans2 = false;
	$formulaire->q9_ans3 = true;

	$formulaire->q10_ans1 = true;

	$formulaire->q11_ans1 = false;

	$formulaire->q12_ans1 = false;
	$formulaire->q12_ans2 = true;

	$formulaire->q13_ans1 = false;
	$formulaire->q13_ans2 = true;
	$formulaire->q13_ans3 = false;

	$formulaire->q14_ans1 = 1;
	$formulaire->q14_ans2 = 2;
	$formulaire->q14_ans3 = 3;
	$formulaire->q14_ans4 = 4;
	$formulaire->q14_ans5 = 5;
	$formulaire->q14_ans6 = 6;
	$formulaire->q14_ans7 = 7;

	$formulaire->utilisateur_id = 22;
	$formulaire = json_encode($formulaire);
	
	echo addHabilitationElectrique($formulaire);*/
?>