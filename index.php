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
        var data1;
        var verts = new Array();
        var vert_att = new Array();
        var vert_att_disp = new Array();
        var lines = new Array();
        var line_att = new Array();
        var meshes = new Array();


        init();
				animate();
        
      	
        	function init() {
  
  
        			//for(var i = 0; i < myVerticesArray.length; i++){
        			for(var i = 0; i < 40; i++){
        					verts[i] = v3(myVerticesArray[i]["X"], myVerticesArray[i]["Y"], myVerticesArray[i]["Z"]);
        					vert_att[i] = myVerticesArray[i]["Connections"];
        					vert_att_disp[i] = myVerticesArray[i]["Deflection"];
        				}
        				
        				//for(var i = 0; i < myCurvesArray.length; i++){
        				for(var i = 0; i < 10; i++){
        					v1_i = myCurvesArray[i]["startVertID"];
        					v1_i = parseInt(v1_i.substring(1));
        					v2_i = myCurvesArray[i]["endVertID"];
        					v2_i = parseInt(v2_i.substring(1));
        					lines[i] = l2(verts[v1_i], verts[v2_i]); 
        					line_att[i] = myCurvesArray[i]["Length"];
        				} 
        			
        			
                var css, light, geometry, material, mesh;

                
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

 								v_rng_start = dispSliderMin;
 								v_rng_end = dispSliderMax;               
                for (i = 0; i < verts.length; i++) {
                	val = vert_att[i];
                	var a_c = (val - v_rng_start)/(v_rng_end - v_rng_start) * 255;
                	material = new THREE.MeshBasicMaterial({color:"#" + ctx(220) + ctx(220) + ctx(220), opacity: 0.5, transparent: true});
                	if(a_c <= 255 && a_c >= 0)
                	{
                		material = new THREE.MeshBasicMaterial({color:"#" + ctx(a_c) + ctx(255-a_c) + ctx(0)});
                	}
                	sphere = new THREE.SphereGeometry(vert_att_disp[i] / 100000000000,  20, 20);
                	mesh = new THREE.Mesh(sphere, material);
                	mesh.position.set(verts[i].x, verts[i].y, verts[i].z);
                	scene.add(mesh);
                }
                
                
                l_rng_start = lengthSliderMin;
                l_rng_end = lengthSliderMax;
                for (i = 0; i < lines.length; i++) {
                	val = line_att[i];
                	var a_c = (val - l_rng_start)/(l_rng_end - l_rng_start) * 255;
 										material = new THREE.MeshBasicMaterial({color:"#" + ctx(220) + ctx(220) + ctx(220), opacity: 0.5, transparent: true});
                	if(a_c <= 255 && a_c >= 0)
                	{
                		material = new THREE.MeshBasicMaterial({color:"#" + ctx(0) + ctx(255-a_c) + ctx(a_c)});
                	}               	
                	line = new THREE.Line(lines[i], material, THREE.LinePieces);  					
                	scene.add(line);
                	//LineSplitter(lines[i], 0, 5, 0, 5, 4);
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