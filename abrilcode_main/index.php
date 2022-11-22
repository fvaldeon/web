<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="abrilCode">
		<meta name="author" content="fvaldeon">
		<link rel="icon" href="img/favicon.ico">
		<title>abrilCode</title>
		<link rel="stylesheet" href="abrilcode-css/abrilcode-index.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	</head>
	<body>

		<div class="topnav" id="myTopnav">
			<img src="img/logo.png" width="50" height="50">
			<a id="title" href="/">abrilcode</a>

			<div class="dropdown" >
				<button class="dropbtn" >DAM 
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
					<a href="https://programacion.abrilcode.com">Programación</a>
					<a href="https://entornos.abrilcode.com">Entornos de Desarrollo</a>
					<a href="https://bbdd.abrilcode.com">Bases de Datos</a>
					<a href="https://ssii.abrilcode.com">Sistemas Informáticos</a>
					<a href="https://adatos.abrilcode.com">Acceso a Datos</a>
					<a href="https://psp.abrilcode.com">Programación de Servicios y Procesos</a>
					<a href="https://interfaces.abrilcode.com">Desarrollo de Interfaces</a>
				</div>
			</div> 
			
			<div class="dropdown" >
				<button class="dropbtn" >DAW 
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
					<a href="https://programacion.abrilcode.com">Programación</a>
					<a href="https://entornos.abrilcode.com">Entornos de Desarrollo</a>
					<a href="https://bbdd.abrilcode.com">Bases de Datos</a>
					<a href="https://ssii.abrilcode.com">Sistemas Informáticos</a>
					<a href="https://despliegue.abrilcode.com">Despliegue de Aplicaciones Web</a>
				</div>
			</div> 


			<div class="dropdown" >
				<button class="dropbtn" >ASIR 
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
					<a href="https://gbbdd.abrilcode.com">Gestion de Bases de Datos</a>
					<a href="https://abbdd.abrilcode.com">Administración de Sistemas Gestores de Bases de Datos</a>  
				</div>
			</div> 
		  
			<div class="dropdown" >
				<button class="dropbtn" >SMR 
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
					<a href="https://seginf.abrilcode.com">Seguridad Informática</a>
				</div>
			</div> 
			<a id="moodle" href="http://moodle.abrilcode.com">Moodle</a>

			<a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
		</div>

		<!-- Cuerpo del sitio -->

		<div class="row1" >
			<div class="card" style="height:100%;"	>
				<div class="main">

					<!-- Grid de tarjetas -->
					<div class="row">
						<div class="column">
							<div class="container">
								<div class="content">
									<img src="img/dam.png" alt="Desarrollo de Aplicaciones Multiplataforma" style="width:100%;">
									
									<h3>DAM</h3>
								</div>
								<div class="overlay">
									<div class="vertical-menu">
										<a href="https://programacion.abrilcode.com">Programación</a>
										<a href="https://entornos.abrilcode.com">Entornos de Desarrollo</a>
										<a href="https://bbdd.abrilcode.com">Bases de Datos</a>
										<a href="https://ssii.abrilcode.com">Sistemas Informáticos</a>
										<a href="https://adatos.abrilcode.com">Acceso a Datos</a>
										<a href="https://psp.abrilcode.com">Programación de Servicios y Procesos</a>
										<a href="https://interfaces.abrilcode.com">Desarrollo de Interfaces</a>
									</div>
								</div>
							</div>
						</div>
						
						<div class="column">
							<div class="container">
								<div class="content">
									<img src="img/daw.png" alt="Desarrollo de Aplicaciones Web" style="width:100%">
									<h3>DAW</h3>
								</div>
								<div class="overlay">
									<div class="vertical-menu">
										<a href="https://programacion.abrilcode.com">Programación</a>
										<a href="https://entornos.abrilcode.com">Entornos de Desarrollo</a>
										<a href="https://bbdd.abrilcode.com">Bases de Datos</a>
										<a href="https://ssii.abrilcode.com">Sistemas Informáticos</a>
										<a href="https://despliegue.abrilcode.com">Despliegue de Aplicaciones Web</a>
									</div>
								</div>
							</div>
						</div>
						<div class="column">
							<div class="container">
								<div class="content">
									<img src="img/asir.png" alt="Administración de Sistemas Informáticos en Red" style="width:100%">
									<h3>ASIR</h3>
								</div>
								<div class="overlay">
									<div class="vertical-menu">
										<a href="https://gbbdd.abrilcode.com">Gestion de Bases de Datos</a>
										<a href="https://abbdd.abrilcode.com">Administración de Sistemas Gestores de Bases de Datos</a>
									</div>
								</div>
							</div>
						</div>
						
						<div class="column">
							<div class="container">
								<div class="content">
									<img src="img/smr.png" alt="Sistemas Microinformáticos y Redes" style="width:100%">
									<h3>SMR</h3>
								</div>
								<div class="overlay">
									<div class="vertical-menu">
										<a href="https://seginf.abrilcode.com">Seguridad Informática</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END GRID -->
				</div>
				<!-- END MAIN -->  
			</div>
			<div class="footer">
				<p><?php echo date("Y"); ?> abrilCode -				
					<a href="mailto:info@abrilcode.com">info@abrilcode.com</a>
				</p>
			</div>
		</div>

		<script>
		function myFunction() {
		  var x = document.getElementById("myTopnav");
		  if (x.className === "topnav") {
			x.className += " responsive";
		  } else {
			x.className = "topnav";
		  }
		}
		</script>

	</body>
</html>
