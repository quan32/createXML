<?php
	//connect mysql
	$hostname="localhost";
	$user="root";
	$passwd="20092137";
	$db="coma-project";
	
	$conn=mysql_connect($hostname,$user,$passwd) or die(mysql_error());
	mysql_select_db($db) or die(mysql_error());


	$xml=simplexml_load_file("source.xml");
	$products = $xml->product;
	$size5=$products->count();
	$string = "";

	// for($m=0;$m<$size5;$m++){
	for($m=0;$m<200;$m++){
		// san pham 1
		$product1 = $products[$m];

		$file_name='schema'.($m+1).'.xsd';
		$file_url='C:\Users\NTQuan\workspace\Coma\sources\schema'.($m+1).'.xsd';
		$xmlFile_url='C:\Users\NTQuan\workspace\Coma\xml\schema'.($m+1).'.xml';
		$image_name='image'.($m+1).'.jpg';
		$image_url='C:\Users\NTQuan\workspace\Coma\images\image'.($m+1).'.jpg';
		$fullname = $product_name=$product1->children()[0];

		// tao file xml
		$product1->saveXML($xmlFile_url);


		$string=
				'<?xml version="1.0" encoding="UTF-8" standalone="no"?>'.
				'<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">';
		$string.=
					'<xs:element name="product">'.
						'<xs:complexType>'.
							'<xs:sequence>';


		//get product infor
		$product_name=$product1->children()[0];
		$product_img=$product1->children()[1];
		$product_price=$product1->children()[2];
		$product_brand=$product1->children()[3];

		//insert into db
		$query="INSERT INTO products(name,image,price,brand,fullname, link)
			values('$file_name','$image_name','$product_price','$product_brand','$fullname','$image_url')";
		mysql_query($query) or die(mysql_error());


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
		

		$handle = fopen($file_url,'w') or die('Cannot create file');
		if(!fwrite($handle, $string))
			die('Cannot write to file');
	}
	fclose($handle);

	//close db connecdt
	mysql_close($conn) or die(mysql_error());

?>
