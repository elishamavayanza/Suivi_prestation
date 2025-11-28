<?php 
class Utilisateur{
  protected  int $id;
  protected string $matricule ;
  protected string $nom;
  protected  string $post_nom;
  protected  string $prenom;
  protected  string $genre;
  protected  $date_naissance;
  protected string $etat_civil;
  protected string $nationalite;
  protected string $adresse;
  protected  string $telephone;
  protected  string $mail;
  protected  string $username;
  protected  string $password;
  protected string $role;
    function __constructor($_nom,$_username,$_password){
        $this->id =0;
        $this->matricule ="XXXXXX";
        $this->nom =$_nom;
        $this->post_nom="XXXXXX";
        $this->prenom ="XXXXXX";
        $this->genre="XXXXXX";
        $this->date_naissance =Date("yyy-mm-dd");
        $this->etat_civil ="XXXXXX";
        $this->nationalite ="XXXXXX";
        $this->adresse="XXXXXX";
        $this->telephone ="XXXXXX";
        $this->mail="XXXXXX";
        $this->username=$_username;
        $this->password =$_password;
        $this->role = "XXXxxxxx";
    }
    /*
    public Utilisateur($id,$mat,$nom,$postnom,$prenom,$genre,$datenaissance,$etatcivil,$nationalite,$adresse,$telephone,$mail,$username,$password,$role){
        $this->id =$id;
        $this->matricule =$mat;
        $this->nom =$nom;
        $this->post_nom=$postnom;
        $this->prenom =$prenom;
        $this->genre=$genre;
        $this->date_naissance =$datenaissance;
        $this->etat_civil =$etatcivil;
        $this->nationalite =$nationalite;
        $this->adresse=$adresse;
        $this->telephone =$telephone;
        $this->mail=$mail;
        $this->username=$username;
        $this->password = $password;
        $this->role = $role;
    }**/

    function SetMatricule($_matricule){
        $this->matricule = $_matricule;
    }
    function GetMatricule(){
        return $this->matricule;
    }

    function SetNom($_nom){
        $this->nom = $_nom;
    }
    public function GetNom(){
        return $this->nom;
    }
    function SetPostNom($_post_nom){
        $this->post_nom = $_post_nom;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
    }
    function GetPostNom(){
        return $this->post_nom ;
    }

    function SetPrenom($_prenom){
        $this->prenom = $_prenom;
    }
    function GetPrenom(){
        return $this->prenom ;
    }

    function SetGenre($_genre){
        $this->genre = $_genre;
    }
    function GetGenre(){
        return $this->genre ;
    }
    function setDateNaissance($_date){
        $this->date_naissance = $_date;
    }
    function GetDateNaissance(){
        return $this->date_naissance;
    }
    function SetEtatCivil($_etat_civil){
        $this->etat_civl = $_etat_civil;
     }
    function GetEtatCivil(){
        return $this->etat_civil;
    }
    function SetNationalite(){
        $this->nationalite;
    }
    function GetNationalite($_nationalite){
        return $this->nationalite = $_nationalite;
    }
    function setAdresse($_adresse){
        $this->adresse = $_adresse;
    }
    function GetAdresse(){
        return $this->adresse;
    }

    function SetTelephone($_telephone){
        $this->telephone = $_telephone;
    }
    function GetTelephone(){
        return $this->telephone;
    }
    function SetMail($_mail){
        $this->mail = $_mail;
    }
    function GetMail(){
        return $this->mal;
    }

    function SetUsername($_username){
        $this->username = $_username;
    }
    function GetUsername(){
        return $this->username;
    }
    function SetPassword($_password){
        $this->password = $_password;
    }
    function GetPassword(){
        return $this->password;
    }
    
    function SetRole($_role){
        $this->role = $_role;
    }
    function GetRole(){
        return $this->role;
    }

    function deconnecter(){

    }
    

    ## Methode proteger 

    protected function seconnecter($username,$password){

    }

    public function creerCompte($_matricule,$_nom,$_postnom,$_prenom,$_genre,$_datenaissance,$_username,$_password){
        $this->SetMatricule($_matricule);
        $this->SetNom($_nom);
        $this->SetPostNom($_postnom);
        $this->SetPrenom($_prenom);
        $this->SetGenre($_genre);
        $this->SetDateNaissance($_datenaissance);
        $this->SetUsername($_username);
        $this->SetPassword($_password);
    }

    
} 
$user = new Utilisateur();
$user->setTelephone ( "0990228797");
#var_dump($user);