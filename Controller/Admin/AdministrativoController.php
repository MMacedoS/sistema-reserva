<?php

class AdministrativoController extends \Controller{
    protected $notas_model;
    protected $turma_model;
    protected $bimestre_model;
    protected $estudante_model;
    protected $app_model;
    protected $disciplina_model;
    protected $professor_model;


    public function __construct() {
        
        $this->validPainel();
        $this->app_model =  new AppModel();
        $this->notas_model = new NotasModel();
        $this->bimestre_model = new BimestreModel();
        $this->turma_model = new TurmaModel();
        $this->estudante_model = new EstudanteModel();         
        $this->disciplina_model = new DisciplinaModel(); 
        $this->professor_model = new ProfessorModel();
    }

    private function validPainel() {        
        if ($_SESSION['painel'] != 'Administrativo' && $_SESSION['painel'] != 'financeiro') {   
            session_start();
            session_destroy();            
            return header('Location: '.$this->url.'/Login');            
        }       
    }

    public function index() {        
        $this->viewAdmin('index');
    }

    public function turmas($request = null) {
        $this->viewAdmin('turmas',$request,"turmas");
    }

    public function estudantes($request = null) {
        $this->viewAdmin('estudantes',$request,"estudantes");
    }

    public function professores($request = null) {
        $this->viewAdmin('professores',$request,"professores");
    }

    public function disciplinas($request = null) {
        $this->viewAdmin('disciplinas',$request,"disciplinas");
    }

    public function bimestres($request = null) {
        $this->viewAdmin('bimestres',$request,"bimestres");
    }

    public function contratos($request = null) {
        $this->viewAdmin('contratos',$request,"listaContratos");
    }

    public function buscaContrato($dados = null) {
        if (is_null($dados))
            return $this->app_model->buscaContrato();
        
        return $this->app_model->buscaContratoPorEstudante($dados);       
    }

    public function buscaTurma($dados = null) {
        if (is_null($dados)){
            return $this->turma_model->buscaTurma();
        }
        return $this->turma_model->buscaTurmaPorNome($dados);
    }

    public function buscaTurmaAtivas() {
        return $this->turma_model->buscaTurmaAtivas();
    }

    public function buscaEstudantes($dados = null) {
        if (is_null($dados))
            return $this->estudante_model->buscaEstudantes();
        if ($dados == 'todos')
            return $this->estudante_model->buscaTodosEstudantes();
        
        return $this->estudante_model->buscaEstudantePorNome($dados);
    }

    public function buscaTodosEstudantes() {
        return $this->estudante_model->buscaTodosEstudantes();
    }

    public function buscaCategoriasTurma() {
        return $this->app_model->buscaCategoriasTurma();
    }

    public function buscaDisciplinas($dados = null) {
        if (is_null($dados)){
            return $this->disciplina_model->buscaDisciplinas();            
        }

        return $this->disciplina_model->buscaDisciplinasPorParams($dados);
    }

    public function buscaProfessor($dados = null) {
        return $this->professor_model->buscaProfessor($dados);
    }

    public function buscaTodosProfessores() {
        return $this->professor_model->buscaTodosProfessores();
    }

    public function buscaBimestres($dados = null) {
        return $this->bimestre_model->bimestres($dados);
    }
    // json retorno

    public function addBimestre() {
        $turmas = $this->bimestre_model->preparaInsertBimestre($_POST);
        echo json_encode($turmas);
    }  

    public function addTurmas() {
        $turmas = $this->turma_model->preparaInsertTurmas($_POST);
        echo json_encode($turmas);
    }  

    public function updTurmas($id) {
        $turmas = $this->turma_model->preparaUpdateTurmas($_POST,$id);
        echo json_encode($turmas);
    }  

    public function buscaTurmasPorId($id) {
        $turmas = $this->turma_model->buscaTurmasPorId($id);
        echo json_encode($turmas[0]);
    }
    
    public function changeStatusTurma($codigo) {
        $turmas = $this->turma_model->changeStatusTurma($codigo);
        echo json_encode($turmas);
    }

   // estudantes
   public function listaEstudantesPorTurma($turma) {
    $estudantes = $this->estudante_model->buscaEstudantePorTurmaEAno($turma,Date('Y'));
    echo json_encode($estudantes);
    }

    public function inserirEstudante() {
        $estudantes = $this->estudante_model->preparaInsertEstudante($_POST);
        echo json_encode($estudantes);
    }

    public function buscaEstudantePorId($id) {
        $estudantes = $this->estudante_model->buscaEstudantePorId($id);
        echo json_encode($estudantes);
    }

    public function updateEstudante($id) {
        $estudantes = $this->estudante_model->preparaUpdateEstudante($_POST, $id);
        echo json_encode($estudantes);
    }

    public function changeStatusEstudante($codigo) {
        $estudantes = $this->estudante_model->changeStatusEstudante($codigo);
        echo json_encode($estudantes);
    }

    public function buscaVinculoTurmaEstudantePorIdEstudante($codigo) {
        $estudantes = $this->estudante_model->vinculoTurmaEstudantePorIdEstudante($codigo);
        echo json_encode($estudantes);
    }

    public function vincularEstudanteTurma() {
        $estudantes = $this->estudante_model->preparaVinculoTurmaEstudante($_POST);
        echo json_encode($estudantes);
    }

    //fim estudantes

    // professores 
    public function InserirProfessor() {
        $estudantes = $this->professor_model->preparaInsertProfessor($_POST);
        echo json_encode($estudantes);
    }

    public function listaProfessorPorTurma($turma) {
        $professores = $this->professor_model->buscaProfessorPorTurmaEAno($turma);
        echo json_encode($professores);
    }

    public function buscaProfessorPorId($id) {
        $professores = $this->professor_model->buscaProfessorPorId($id);
        echo json_encode($professores);
    }

    public function updateProfessor($id) {
        $professors = $this->professor_model->preparaUpdateProfessor($_POST, $id);
        echo json_encode($professors);
    }

    public function changeStatusProfessor($codigo) {
        $professors = $this->professor_model->changeStatusProfessor((int)$codigo);
        echo json_encode($professors);
    }

    //fim professores

    // disicplinas

    public function buscaDisciplina($id) {
        $disciplinas = $this->disciplina_model->buscaDisciplina();
        echo json_encode($disciplinas);
    }

    public function buscaTurmaPorCategoria($id) {
        $turmas = $this->turma_model->buscaTurmaPorCategoria((int)$id);
        echo json_encode($turmas);
    }

    public function buscaProfessorParaSelect()
    {
        echo json_encode($this->buscaProfessor());
    }

    public function buscaDisciplinaPorId($id) {
        $disciplinas = $this->disciplina_model->buscaDisciplinaPorId($id);
        echo json_encode($disciplinas);
    }

    public function salvarDiciplinas()
    {
        $disciplinas = $this->disciplina_model->prepareInsertDisciplinas($_POST);
        echo json_encode($disciplinas);
    }

    public function updateDisciplina($id)
    {
        $disciplinas = $this->disciplina_model->preparaUpdateDisciplinas($_POST,$id);
        echo json_encode($disciplinas);
    }

    public function criandoDiciplinas()
    {
        $disciplinas = $this->disciplina_model->prepareInsertListaDisciplinas($_POST);
        echo json_encode($disciplinas);
    }

    public function criandoCarga()
    {
        $disciplinas = $this->disciplina_model->prepareInsertCarga($_POST);
        echo json_encode($disciplinas);
    }

    public function diasSemanaAulasDisciplina()
    {
        $disciplinas = $this->disciplina_model->prepareDiasSemanaAulasDisciplina($_POST);
        echo json_encode($disciplinas);
    }

    public function removeDiaSemanaTurma($id) 
    {
        $disciplinas = $this->disciplina_model->removeDiaSemanaTurma($id);
        echo json_encode($disciplinas);
    }

    public function diaSemanaTurma($id){
        $dias = $this->disciplina_model->diaSemanaTurma($id);
        echo json_encode($dias);
    }

    public function buscaCargaParaSelect() {
        $disciplinas = $this->disciplina_model->buscaCargaParaSelect($_POST);
        echo json_encode($disciplinas);
    }

    public function changeStatusDisciplina($codigo) {
        $disciplinas = $this->disciplina_model->changeStatusDisciplina((int)$codigo);
        echo json_encode($disciplinas);
    }

    public function changeStatusBg($code) {
        $bg = $this->app_model->changeStatusBg($code);
        return $bg;
    }

    public function changeStatusBgGet($code) {
        $bg = $this->app_model->changeStatusBg($code);
        echo json_encode($bg);
    }
      
}