
var app = angular.module('ABMangularPHP', ['ui.router', 'angularFileUpload','satellizer'])//esto permite incluir el módulo 'ui.router' al módulo 'ABMangularPHP'

		.config(function($stateProvider, $urlRouterProvider,$authProvider){

		$authProvider.loginUrl = 'genericParcial/PHP/clases/Autentificador.php';
		$authProvider.signupUrl = 'genericParcial/PHP/clases/Autentificador.php';
		$authProvider.tokenName = 'tokenTest';
		$authProvider.tokenPrefix = 'ejemploabm';
		$authProvider.authHeader = 'data';

		$stateProvider
		.state('menu',
			{url: '/menu',
			templateUrl: 'vistas/menu.html',
			controller: 'controlMenu'})

		.state('alta',
			{url: '/alta',
			templateUrl: 'vistas/altaObjeto.html',
			controller: 'controlAlta'})

		.state('login',
			{url: '/login',
			templateUrl: 'login.html',
			controller: 'controlLogin'})

		.state('modificacion',
			{url: '/modificacion/{id}?:atr:foto',
			templateUrl: 'vistas/altaObjeto.html',
			controller: 'controlModificacion'})

		.state('grilla',
			{url: '/grilla',
			templateUrl: 'vistas/grillaObjetos.html',
			controller: 'controlGrilla'});
		$urlRouterProvider.otherwise('/login');
	});

	/*
	* controlMenu
	*/
	app.controller('controlMenu', function($scope, $http, $auth, $state) {

	   $scope.DatoTest="**Menu**";
	   $scope.Logout=function() {
	   $auth.logout()
		   .then(function(){
			   $state.go("login");
		   });
	   };
	});
	/*
	 * controlLogin
	 */
	app.controller('controlLogin', function($scope,$auth,$state) {
	  	$scope.DatoTest="**Login**";
		$scope.correo="admin@admin";
		$scope.clave="123456";
		$scope.Login=function()
		{
			$auth.login({correo:$scope.correo, clave:$scope.clave})
			.then(function(respuesta){
				// console.log(respuesta);
				if($auth.isAuthenticated()){
					console.info($auth.isAuthenticated(), $auth.getPayload());
					$state.go("menu");
				}else{
					alert("No se encontró el usuario. Verifique los datos.");
				}
			});
		};
		$scope.crearCuenta=function(){
			$state.go("alta");
		};// fin cargar formulario
	});

	/*
	 * controlAlta
	 */
	app.controller('controlAlta', function($scope, $http, $state,$auth, FileUploader) {
	  	if($auth.isAuthenticated()){
		 	$scope.DatoTest="**alta**";
		 	//inicio las variables
		 	$scope.uploader=new FileUploader({url:'PHP/nexo.php'});
		 	$scope.objeto={};
		 	$scope.objeto.atr= "puchi" ;
		 	// $scope.objectVO.edad= "5" ;
		 	// $scope.objectVO.tipo= "perro" ;
		 	// $scope.objectVO.foto="pordefecto.png";
		 	// $scope.objectVO.fechaNacimiento;
		 	// $scope.objectVO.sexo;
		 	$scope.cargarfotos = function(nombrefoto){

				var direccion = "fotos/"+nombrefoto;
				$http.get(direccion,{responseType: "blob"})
				.then(function(respuesta) {
					var mimetype = respuesta.data.type;
					var archivo = new File([respuesta.data], direccion, {type: mimetype});
					var dummi = new FileUploader.FileItem($scope.uploader, {});
					dummi._file = archivo;
					dummi.file = {};
					dummi.file = new File([respuesta.data], nombrefoto, {type: mimetype});
					$scope.uploader.queue.push(dummi);
				});
		  	};
	  		$scope.cargarfotos($scope.objeto.foto);
	  		//$scope.foto="fotos/pordefecto.png";
	  		//$scope.objectVO.foto="fotos/pordefecto.png";
	  		$scope.uploader.onSuccessItem = function(item, response, status, headers){

				$http.post('PHP/nexo.php', { datos: {accion :"insertar",objeto:$scope.objeto}})
					.then(function(respuesta) {
						 console.log(respuesta.data);
						 $state.go("grilla");
					},function errorCallback(response){
					console.log(response);});
				// console.info("Ya guardé el archivo.", item, response, status, headers);
		  	};
			$scope.Guardar = function(){
				// console.log($scope.uploader.queue);
				if($scope.uploader.queue[0]!=undefined){
					var nombrefoto = $scope.uploader.queue[0]._file.name;
					$scope.objeto.foto=nombrefoto;
				}
				$scope.uploader.uploadAll();
				// console.log("objectVO a guardar:");
				// console.log($scope.objectVO);
			}
		}else{
			$state.go("login");
		}
	});


	app.controller('controlGrilla', function($scope, $http, $state, $auth) {
		$scope.DatoTest="**grilla**";
		if($auth.isAuthenticated()){
			//$http.get("http://www.mocky.io/v2/57c8ab94120000be13e76a92")
			$http.get('PHP/nexo.php', { params: {accion :"traer"}})
			.then(function(respuesta) {

				 $scope.listadoObjetos = respuesta.data.listado;
				 //$scope.listadoObjetos = respuesta.data;
				 // console.log(respuesta.data);

			},function errorCallback(response) {
					 $scope.listadoObjetos = [];
					console.log(response);
			 });
			$scope.Borrar = function(objeto){
				$http.post("PHP/nexo.php",{datos:{accion :"borrar",objeto:objeto}},{headers: {'Content-Type': 'application/x-www-form-urlencoded'}})

					.then(function(respuesta) {

						console.log(respuesta.data);
						$http.get('PHP/nexo.php', { params: {accion :"traer"}})
							.then(function(respuesta) {

								$scope.listadoObjetos = respuesta.data.listado;

							},function errorCallback(response) {

								$scope.listadoObjetos= [];
								console.log(response);
							});
					},function errorCallback(response) {
						console.log( response);
					});
			}
	 	}else{
		$state.go("login");
	 	}
	});
app.controller('controlModificacion', function($scope, $http, $state, $stateParams, FileUploader, cargadordefotos)//, $routeParams, $location)
{
	$scope.objeto={};
	$scope.DatoTest="**Modificar**";
	$scope.uploader=new FileUploader({url:'PHP/nexo.php'});
	$scope.objeto.id=$stateParams.id;
	$scope.objeto.atr=$stateParams.atr;
	$scope.objeto.foto=$stateParams.foto;
  	cargadordefotos.cargarfoto($scope.objeto.foto, $scope.uploader);
	$scope.uploader.onSuccessItem=function(item, response, status, headers){

		$http.post('PHP/nexo.php', { datos: {accion :"modificar",objeto:$scope.objeto}})
		.then(function(respuesta) {
			$state.go("grilla");
		},
		function errorCallback(response){
			console.log( response);
		});
	};
	$scope.Guardar = function(){
		if($scope.uploader.queue[0]!=undefined)
		{
			var nombrefoto = $scope.uploader.queue[0]._file.name;
			$scope.objeto.foto = nombrefoto;
		}
		$scope.uploader.uploadAll();
		$http.post('PHP/nexo.php', { datos: {accion :"modificar",objeto:$scope.objeto}})
		.then(function(respuesta) {
			$state.go("grilla");
		},
		function errorCallback(response){
			console.log( response);
		});
	}
});

	app.service('cargadordefotos',function($http, FileUploader){
		this.cargarfoto=function(nombrefoto, uploader){

			var direccion = "fotos/"+nombrefoto;
			$http.get(direccion,{responseType: "blob"})
			.then(function(respuesta) {

				var mimetype = respuesta.data.type;
				var archivo = new File([respuesta.data], direccion, {type: mimetype});
				var dummi = new FileUploader.FileItem(uploader, {});
				dummi._file = archivo;
				dummi.file = {};
				dummi.file = new File([respuesta.data], nombrefoto, {type: mimetype});
				uploader.queue.push(dummi);
			});

		}
	});
