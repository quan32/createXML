<?php
	$xml=simplexml_load_file("source.xml");
	$products = $xml->product;
	$size5=$products->count();
	$string = "";

	for($m=0;$m<$size5;$m++){
		// san pham 1
		$product1 = $products[$m];
		$string=
				'<?xml version="1.0" encoding="UTF-8" standalone="no"?>'.
				'<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">';
		$string.=
					'<xs:element name="product">'.
						'<xs:complexType>'.
							'<xs:sequence>';
		$size=$product1->children()->count();
		for($i=0;$i<$size;$i++){
			$children=$product1->children()[$i];
			if($children->children()->count()==0){
				$string.=
				'<xs:element name="'.$children->getName().'">'.
					'<xs:complexType>'.
						'<xs:sequence>'.
							'<xs:element name="'.$children.'" type="xs:string" />'.
						'</xs:sequence>'.
					'</xs:complexType>'.
				'</xs:element>';
			}else{
				$string.=
					'<xs:element name="Options">'.
						'<xs:complexType>'.
							'<xs:sequence>';

				$options = $children->children();
				$size1=$options->count();

				for($j=0;$j<$size1;$j++){
					//Su ly voi moi option
					$option = $options[$j];
			
					$string.=
					'<xs:element name="'.$option->children()[0].'">'.
						'<xs:complexType>'.
							'<xs:sequence>';

					$values = $option->children()[1];
					// var_dump($values);die;
					
					$value = $values->children();
					$size2 = $value->count();
					for($k=0;$k<$size2;$k++){
						$string.='<xs:element name="'.$value[$k].'" type="xs:string" />';
					}

					$string.=
							'</xs:sequence>'.
						'</xs:complexType>'.
					'</xs:element>';

				}
				$string.=
							'</xs:sequence>'.
						'</xs:complexType>'.
					'</xs:element>';
			}
		}
		$string.=
							'</xs:sequence>'.
						'</xs:complexType>'.
					'</xs:element>';
		$string.='</xs:schema>';
		
		
		$filename='xml/schema'.$m.'.xsd';
		$handle = fopen($filename,'w') or die('Cannot create file');
		if(!fwrite($handle, $string))
			die('Cannot write to file');
	}
	fclose($handle);

?>
