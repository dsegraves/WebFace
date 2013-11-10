<!doctype html5>
<html lang=en>
<head>
    <title>Geo Display Test</title>
    <meta charset=utf-8/>
    <meta name=viewport content='width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0'/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
    <script type='text/javascript' src='scripts/bh_jqui.js'></script>
    <link rel='stylesheet' type='text/css' href='http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css'/>
    <link rel='stylesheet' type='text/css' href='css/960_12_col.css'/>
    <link rel='stylesheet' type='text/css' href='css/style.css'/>
    <script src = http://mrdoob.github.com/three.js/examples/js/Detector.js ></script>
    <script src = http://mrdoob.github.com/three.js/build/three.min.js ></script>
    <script src = http://mrdoob.github.com/three.js/examples/js/controls/TrackballControls.js> </script>
    <script src = http://mrdoob.github.com/three.js/examples/js/libs/stats.min.js ></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript"> google.load('visualization', '1', { packages: ['linechart'] }); </script>

    <?php

        require_once("db_connect.php");
        //get select fields from employee table AND from department table (informal join)
        //$sql = "SELECT empId, lastName, firstName, departmentName, position, salary FROM employee, departments WHERE department = deptID";
        $sql = "SELECT *  FROM Curves";
        $curvesResult = mysql_query($sql) or die(mysql_error);

        $sql = "SELECT *  FROM Vertices";
        $verticesResult = mysql_query($sql) or die(mysql_error);

        mysql_close();

        $curvesRows = array();
        //use while to loop through all rows:
        while($row = mysql_fetch_array($curvesResult))
        {    
            $curvesRows[] = $row;
        }

        $verticesRows = array();
        //use while to loop through all rows:
        while($vrow = mysql_fetch_array($verticesResult))
        {    
            $verticesRows[] = $vrow;
        }
      
?>


<script type='text/javascript'>
    var myCurvesArray = <?php echo json_encode($curvesRows); ?>;
    var myVerticesArray = <?php echo json_encode($verticesRows); ?>;
</script>

<!--
-->

</head>
<body>


<script>
        if ( ! Detector.webgl ) { Detector.addGetWebGLMessage(); }

        var renderer, scene, camera, controls, stats;
        var cube, plane, colors = [];
        var group = new THREE.Object3D();
        var info;
        //var scene;
        var data1;
        var data2;
        var verts = new Array();
        var vert_att = new Array();
        var lines = new Array();
        var line_att = new Array();
        var meshes = new Array();


        CallGoogle();
        animate();
 
        
      	function CallGoogle() {
        	//Spreadsheet call
        	google.load("visualization", '1', {packages:['corechart']});
        	var query1 = new google.visualization.Query(
      			'https://docs.google.com/spreadsheet/ccc?key=0Au4jT9H1H9iBdHVCMFd3Tzg2TEN0RXlhRmtGWXVEc3c#gid=1');
  				query1.setQuery('select *');
  				query1.send(handleQueryResponse);
  			}
        	  	
        function handleQueryResponse(response) {
						data1 = response.getDataTable();
						init();
				}
				
      	
        	function init() {
  
						var l = 0;
						var m = 0;
						
        			for(var i = 0; i<data1.getNumberOfRows(); i++){
        				if (data1.getValue(i,0) == "Vertex") {
        					verts[i] = v3(data1.getValue(i,1), data1.getValue(i,2), data1.getValue(i,3));
        					vert_att[i] = data1.getValue(i,6);
        				}else if (data1.getValue(i,0) == "Line") {
        					lines[l] = l2(verts[data1.getValue(i,1)], verts[data1.getValue(i,2)]); 
        					l++;
        				} else if (data1.getValue(i,0) == "Mesh") {
        					meshes[m] = s4(data1.getValue(i,2),data1.getValue(i,3),data1.getValue(i,4),data1.getValue(i,5));
        					m++;
        				}
        			}
        			
                var css, light, geometry, material, mesh;
        
                //css = document.body.appendChild( document.createElement('style') );
                //css.innerHTML = 'body {background-color: transparent; font: 600 12pt monospace; margin: 0; overflow: hidden; text-align: center; }' +
                //        'a { color: #f00; text-decoration: none;} ' +
                //        'button, input[type=range] { -webkit-appearance: none; background-color: silver; height:20px; opacity: 0.5; width: 80px;}' +
                //        'input[type="range"]::-webkit-slider-thumb {-webkit-appearance: none; background-color: #666; opacity: 0.5; width: 10px; height: 26px; }' ;


                //info = document.createElement('div');
                //info.id = "bh";
        	    //document.body.appendChild( info );



                // info.style.cssText = 'margin 0 0 0 100px: auto; position: absolute; top: 0px; width: 100% ';
                //info.style.cssText = 'position: absolute; width: 100%; ';
                //info.innerHTML = info.txt = '<h1>Geo Display Test</h1>' ;

                
                scene = new THREE.Scene();
                if ( ! Detector.webgl ) {
                        renderer = new THREE.CanvasRenderer( { antialias: true } );
                } else {
                        renderer = new THREE.WebGLRenderer( { antialias: true } );
                }
                renderer.setSize( window.innerWidth, window.innerHeight );
                renderer.setClearColor(0xffffff, 1);
                renderer.shadowMapEnabled = true;
        	    //document.body.appendChild( renderer.domElement );
                document.getElementById("threejs").appendChild(renderer.domElement);
                
                camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 1000 );
                camera.position.set( -100, 100, 100 );
                controls = new THREE.TrackballControls( camera, renderer.domElement );

                window.addEventListener( 'resize', onWindowResize, false );

// Lights
                light = new THREE.AmbientLight( 0xffffff );
                light.color.setHSL( 0.1, 0.5, 0.5 );
                scene.add( light );
                                               

// Axes                
        				//scene.add( new THREE.ArrowHelper( v(1, 0, 0), v(0, 0, 0), 100, 0xcc0000) );
        				//scene.add( new THREE.ArrowHelper( v(0, 1, 0), v(0, 0, 0), 100, 0x00cc00) );
        				//scene.add( new THREE.ArrowHelper( v(0, 0, 1), v(0, 0, 0), 100, 0x0000cc) );

// Objects

 								v_rng_start = 0;
 								v_rng_end = 5;               
                for (i = 0; i < verts.length; i++) {
                	val = vert_att[i];
                	var a_c = (val - v_rng_start)/(v_rng_end - v_rng_start) * 255;
                	material = new THREE.MeshBasicMaterial({color:"#" + ctx(220) + ctx(220) + ctx(220)});
                	if(a_c <= 255 && a_c >= 0)
                	{
                		material = new THREE.MeshBasicMaterial({color:"#" + ctx(a_c) + ctx(255-a_c) + ctx(0)});
                	}
                	sphere = new THREE.SphereGeometry(vert_att[i],  20, 20);
                	mesh = new THREE.Mesh(sphere, material);
                	mesh.position.set(verts[i].x, verts[i].y, verts[i].z);
                	scene.add(mesh);
                }
                
                
                for (i = 0; i < lines.length; i++) {
                	//material = new THREE.LineBasicMaterial({color:"#" + ctx(255) + ctx(0) + ctx(0)}, 4, 'round', 'round', true); 
                	//line = new THREE.Line(lines[i], material, THREE.LinePieces);  					
                	//scene.add(line);
                	LineSplitter(lines[i], 0, 5, 0, 5, 10);
                }
                 
                
        }
        
        function LineSplitter (line, start_val, end_val, start_rng, end_rng, seg_num) {
        	verts = line.vertices;
        	v1 = verts[0];
        	v2 = verts[1];
        	for (i = 0; i < seg_num; i++) {
        		va = new THREE.Vector3();
        		va.subVectors(v2, v1);
        		va.multiplyScalar(i/seg_num);
        		va.addVectors(va,v1);
        		vb = new THREE.Vector3();
        		vb.subVectors(v2,v1);
        		vb.multiplyScalar((i+1)/seg_num);
        		vb.addVectors(vb,v1);
        		l_geo = l2(va, vb);
        		val = (end_val - start_val)*(i/seg_num) + start_val;
        		val = (val - start_rng)/(end_rng - start_rng);
        		col_val = Math.round(val * 255);
        		material = new THREE.LineBasicMaterial({color:"#" + ctx(col_val) + ctx(255-col_val) + ctx(0)}, 4, 'round', 'round', true); 
        		l_new = new THREE.Line(l_geo, material, THREE.LinePieces);
        		scene.add(l_new);
        	}
        }
        
        
        function v(x,y,z){ return new THREE.Vector3(x,y,z); }
        function v3(x, y, z){ return new THREE.Vector3( x, y, z ); }
        function l2(v1,v2) {
        				var line_verts = new THREE.Geometry();
                line_verts.vertices.push(v1);
                line_verts.vertices.push(v2);
                return line_verts;
              }
        function s4(a,b,c,d)
        {				shape = new THREE.Shape([ verts[a], verts[b], verts[c], verts[d] ]);
                //shape.fromPoints( [ verts[a], verts[b], verts[c], verts[d] ] );
                return shape;
        }
        
        
        
        
        function ctx(c) {
    			var hex = c.toString(16);
    			return hex.length == 1 ? "0" + hex : hex;
				}

        function animate() {
                requestAnimationFrame( animate );
                //controls.update();
                renderer.render( scene, camera );
        }

        function onWindowResize() {
                windowHalfX = window.innerWidth / 2;
                windowHalfY = window.innerHeight / 2;
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize( window.innerWidth, window.innerHeight );
        }
        
        function colorful( arr, count) {
                var delta = 1.0 / count;
                for (var i = 0; i < count; i++ ) {
                        arr.push( i * delta );
                }
                return arr;
        }        
        
        
</script>


<!-- BH UI CODE -->
    
    <div class = "container_12" style = "background-color: transparent; position:absolute" left:300px; top:150px;">
	    <div class = "grid_12">
	        <h1 class ="UI">
	    			Thornton Tomasetti
	    	</h1>
            <h2 class ="UI">
                Structural Query Engine - Prototype #1123
            </h2>
	    </div>
	    <div class = "clear"></div>
        <div class = "grid_4" id ="dragMe" style ="background-color:rgba(225,225,225,0.3)">
	    		<h3>
	    			Structural Data
	    		</h3>
	    		<div class="accord" >
		            <h3 >Framing Member Length</h3>
		            <div >
                        <p>
                            <label for="length">Length range:</label>
                            <input type ="text" id="length" style ="border: 0; font-weight: bold;" />
                        </p>
		                <div id="lengthSlider"></div>
		            </div>
        		</div>
                <div class="accord">
		            <h3 >Node Displacement</h3>
		            <div >
                        <p>
                            <label for="disp">Displacement range:</label>
                            <input type ="text" id="disp" style ="border: 0; font-weight: bold;" />
                        </p>
		                <div id="dispSlider"></div>
		            </div>
        		</div>
	    	</div>
     </div>
    <div id ="threejs"></div>

</body>
</html>