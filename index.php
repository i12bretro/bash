<?php
	header('Content-Type: text/plain');
	$i=0;
	$scripts = array();
	
	echo "#!/bin/bash\n\n".
	"comment=`tput setaf 2`\n".
	"prompt=`tput setaf 6`\n".
	"reset=`tput sgr0`\n".
	"date=`date '+%m/%d/%Y %H:%M:%S'`\n".
	"INPUT=\"\"\n".
	"CONFIRMATION=\"\"\n".
	"BASEURL=\"".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].str_ireplace('index.php','',$_SERVER['SCRIPT_NAME'])."\"\n".
	"\n";
	
	foreach (glob("*.sh") as $script){
		$i++;
		$scripts[] = "\"".$script."\"";
	}
	
	echo "scriptsArray=(\"\" ".implode(" ",$scripts).")\n";
	echo "echo -e \"\\n\"\n";
	echo "function showMenu(){\n".
		"echo -e \"\\n\${prompt}******************* MENU *******************\\n\${reset}\"\n".
		"for(( n=1; n<=".$i."; n++ ))\n".
		"do\n".
			"echo -e \"\$(printf %02d \$n)..........\${scriptsArray[\${n}]}\"\n".
		"done\n".
		"echo -e \"\\n\"\n".
		"read -r -p \"\${prompt}Make a selection from the menu above (1-".$i."): \${reset}\" INPUT </dev/tty\n".
		"if [[ ! \${INPUT} =~ ^[0-9]+$ ]] || [ \${INPUT} -gt ".$i." ] || [ \${INPUT} -lt 1 ]; then\n".
			"echo -e \"\\nInvalid selection, please select from the menu\\n\"\n".
			"showMenu\n".
			"echo -e \"\\n\"\n".
		"else\n".
			"echo -e \"\"\n".
			"read -r -p \"\${prompt}Are you sure you want to execute \${scriptsArray[\${INPUT}]}? (y/N): \${reset}\" CONFIRMATION </dev/tty\n".
			"if [[ \${CONFIRMATION} =~ ^[Yy]$ ]]; then\n".
			"echo -e \"\\n\\nExecuting \${BASEURL}\${scriptsArray[\${INPUT}]}\\n\\n\"\n".
			"curl -s \${BASEURL}\${scriptsArray[\${INPUT}]} | bash\n".
			"else\n".
			"showMenu\n".
			"echo -e \"\\n\"\n".
			"fi\n".
		"fi\n".
	"}\n".
	"showMenu\n";
?>