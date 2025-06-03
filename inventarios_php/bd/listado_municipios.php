<?php
$municipios = [
    "Alta Verapaz" => ["Cobán", "San Pedro Carchá", "Santa Cruz Verapaz", "San Cristóbal Verapaz", "Tactic", "Tamahú", "Tucurú", "Panzós", "Senahú", "Cahabón", "Chisec", "Chahal", "Fray Bartolomé de las Casas", "Santa Catalina La Tinta", "Raxruhá"],
    "Baja Verapaz" => ["Salamá", "San Miguel Chicaj", "Rabinal", "Cubulco", "Granados", "Santa Cruz El Chol", "San Jerónimo", "Purulhá"],
    "Chimaltenango" => ["Chimaltenango", "San José Poaquil", "San Martín Jilotepeque", "Comalapa", "Santa Apolonia", "Tecpán Guatemala", "Patzún", "Pochuta", "Patzicía", "Santa Cruz Balanyá", "Acatenango", "Yepocapa", "San Andrés Itzapa", "Parramos", "Zaragoza", "El Tejar"],
    "Chiquimula" => ["Chiquimula", "San José La Arada", "San Juan Ermita", "Jocotán", "Camotán", "Olopa", "Esquipulas", "Concepción Las Minas", "Quezaltepeque"],
    "El Progreso" => ["Guastatoya", "Morazán", "San Agustín Acasaguastlán", "San Cristóbal Acasaguastlán", "El Jícaro", "Sansare", "Sanarate", "San Antonio La Paz"],
    "Escuintla" => ["Escuintla", "Santa Lucía Cotzumalguapa", "La Democracia", "Siquinalá", "Masagua", "Tiquisate", "La Gomera", "Guanagazapa", "Puerto San José", "Iztapa", "Palín", "San Vicente Pacaya", "Nueva Concepción"],
    "Guatemala" => ["Guatemala", "Santa Catarina Pinula", "San José Pinula", "San José del Golfo", "Palencia", "Chinautla", "San Pedro Ayampuc", "Mixco", "San Pedro Sacatepéquez", "San Juan Sacatepéquez", "San Raymundo", "Chuarrancho", "Fraijanes", "Amatitlán", "Villa Nueva", "Villa Canales", "Petapa"],
    "Huehuetenango" => ["Huehuetenango", "Chiantla", "Malacatancito", "Cuilco", "Nentón", "San Pedro Necta", "Jacaltenango", "San Pedro Soloma", "San Ildefonso Ixtahuacán", "Santa Bárbara", "La Libertad", "La Democracia", "San Miguel Acatán", "San Rafael La Independencia", "Todos Santos Cuchumatán", "San Juan Atitán", "Santa Eulalia", "San Mateo Ixtatán", "Colotenango", "San Sebastián Huehuetenango", "Tectitán", "Concepción Huista", "San Juan Ixcoy", "San Antonio Huista", "San Sebastián Coatán", "Barillas", "Aguacatán", "San Rafael Petzal", "San Gaspar Ixchil", "Santiago Chimaltenango", "Santa Cruz Barillas", "San Juan Chamelco", "San Marcos"],
    "Izabal" => ["Puerto Barrios", "Livingston", "El Estor", "Morales", "Los Amates"],
    "Jalapa" => ["Jalapa", "San Pedro Pinula", "San Luis Jilotepeque", "San Manuel Chaparrón", "San Carlos Alzatate", "Monjas", "Mataquescuintla"],
    "Jutiapa" => ["Jutiapa", "El Progreso", "Santa Catarina Mita", "Agua Blanca", "Asunción Mita", "Yupiltepeque", "Atescatempa", "Jerez", "El Adelanto", "Zapotitlán", "Comapa", "Jalpatagua", "Conguaco", "Moyuta", "Pasaco", "San José Acatempa", "Quesada"],
    "Petén" => ["Flores", "San José", "San Benito", "San Andrés", "La Libertad", "San Francisco", "Santa Ana", "Dolores", "San Luis", "Poptún", "Melchor de Mencos", "Sayaxché"],
    "Quetzaltenango" => ["Quetzaltenango", "Salcajá", "Olintepeque", "San Carlos Sija", "Sibilia", "Cabricán", "Cajolá", "San Miguel Sigüilá", "Ostuncalco", "Concepción Chiquirichapa", "San Martín Sacatepéquez", "Almolonga", "Cantel", "Huitán", "Zunil", "Colomba", "San Francisco La Unión", "El Palmar", "Coatepeque", "Génova", "Flores Costa Cuca", "La Esperanza", "Palestina de Los Altos"],
    "Quiché" => ["Santa Cruz del Quiché", "Chiché", "Chinique", "Zacualpa", "Chajul", "Santo Tomás Chichicastenango", "Patzité", "San Antonio Ilotenango", "San Pedro Jocopilas", "Cunén", "San Juan Cotzal", "Joyabaj", "Nebaj", "San Andrés Sajcabajá", "Uspantán", "Sacapulas", "San Bartolomé Jocotenango", "Canillá", "Chicamán", "Ixcán", "Pachalum"],
    "Retalhuleu" => ["Retalhuleu", "San Sebastián", "Santa Cruz Muluá", "San Martín Zapotitlán", "San Felipe", "San Andrés Villa Seca", "Champerico", "Nuevo San Carlos", "El Asintal"],
    "Sacatepéquez" => ["Antigua Guatemala", "Jocotenango", "Pastores", "Sumpango", "Santo Domingo Xenacoj", "Santiago Sacatepéquez", "San Bartolomé Milpas Altas", "San Lucas Sacatepéquez", "Santa Lucía Milpas Altas", "Magdalena Milpas Altas", "Santa María de Jesús", "Ciudad Vieja", "San Miguel Dueñas", "Alotenango", "San Antonio Aguas Calientes"],
    "San Marcos" => ["San Marcos", "San Pedro Sacatepéquez", "Comitancillo", "San Antonio Sacatepéquez", "Tejutla", "Río Blanco", "San Lorenzo", "San Sebastián", "Concepción Tutuapa", "Tacaná", "Sibinal", "Tajumulco", "Catarina", "Ayutla", "Ocos", "San Cristóbal Cucho", "El Quetzal", "La Reforma", "Pajapita", "Ixchiguán", "San José Ojetenam", "San Rafael Pie de La Cuesta", "Nuevo Progreso", "El Tumbador", "El Rodeo", "Malacatán", "Cacahoatán", "Esquipulas Palo Gordo", "La Blanca"],
    "Santa Rosa" => ["Cuilapa", "Barberena", "Santa Rosa de Lima", "Casillas", "San Rafael Las Flores", "Oratorio", "San Juan Tecuaco", "Chiquimulilla", "Taxisco", "Santa María Ixhuatán", "Guazacapán", "Santa Cruz Naranjo", "Pueblo Nuevo Viñas", "Nueva Santa Rosa"],
    "Sololá" => ["Sololá", "San José Chacayá", "Santa María Visitación", "Santa Lucía Utatlán", "Nahualá", "Santa Catarina Ixtahuacán", "Santa Clara La Laguna", "Concepción", "San Andrés Semetabaj", "Panajachel", "Santa Catarina Palopó", "San Antonio Palopó", "San Lucas Tolimán", "Santa Cruz La Laguna", "San Juan La Laguna", "San Pablo La Laguna", "San Marcos La Laguna", "San Pedro La Laguna", "Santiago Atitlán"],
    "Suchitepéquez" => ["Mazatenango", "Cuyotenango", "San Francisco Zapotitlán", "San Bernardino", "San José El Idolo", "Santo Domingo Suchitepéquez", "Patulul", "Santa Bárbara", "San Juan Bautista", "Santo Tomás La Unión", "Zunilito", "Samayac", "San Pablo Jocopilas", "San Antonio Suchitepéquez", "San Miguel Panán", "Chicacao", "Rio Bravo", "Cuyotenango", "San Lorenzo", "Pueblo Nuevo", "San Gabriel"],
    "Totonicapán" => ["Totonicapán", "San Cristóbal Totonicapán", "San Francisco El Alto", "San Andrés Xecul", "Momostenango", "Santa María Chiquimula", "Santa Lucía La Reforma", "San Bartolo"],
    "Zacapa" => ["Zacapa", "Estanzuela", "Río Hondo", "Gualán", "Teculután", "Usumatlán", "Cabañas", "San Diego", "La Unión", "Huité"]
];

$json = [];

if (isset($_GET['q'])) {
    $search = strtolower($_GET['q']);
    
    foreach ($municipios as $departamento => $municipios_departamento) {
        foreach ($municipios_departamento as $municipio) {
            if (strpos(strtolower($municipio), $search) !== false) {
                $json[] = ['id' => $municipio, 'text' => $municipio];
            }
        }
    }
} else {
    foreach ($municipios as $departamento => $municipios_departamento) {
        foreach ($municipios_departamento as $municipio) {
            $json[] = ['id' => $municipio, 'text' => $municipio];
        }
    }
}

echo json_encode($json);
?>
