<?php
require_once("../script/config.php");

// 获取财务报告数据
try {
    // 获取日期过滤参数
    $dateDebut = isset($_GET['date_debut']) ? $_GET['date_debut'] : null;
    $dateFin = isset($_GET['date_fin']) ? $_GET['date_fin'] : null;
    
    // 构建基础查询
    $totalPaymentsSql = "SELECT SUM(montant) as total FROM honoraire";
    $totalTeachersSql = "SELECT COUNT(DISTINCT matricule_enseignant) as total FROM honoraire";
    $totalCoursesSql = "SELECT COUNT(DISTINCT code_cours) as total FROM honoraire";
    $teacherPaymentsSql = "SELECT e.nom, e.postnom, e.prenom, c.nomComplet as cours, h.montant, h.devise, h.datesve
                           FROM honoraire h
                           JOIN enseignant e ON h.matricule_enseignant = e.matriculeEnseignant
                           JOIN cours c ON h.code_cours = c.code_cours";
    
    // 如果提供了日期过滤器，则添加WHERE子句
    if ($dateDebut && $dateFin) {
        $totalPaymentsSql .= " WHERE datesve BETWEEN :dateDebut AND :dateFin";
        $totalTeachersSql .= " WHERE datesve BETWEEN :dateDebut AND :dateFin";
        $totalCoursesSql .= " WHERE datesve BETWEEN :dateDebut AND :dateFin";
        $teacherPaymentsSql .= " WHERE h.datesve BETWEEN :dateDebut AND :dateFin";
    }
    
    $teacherPaymentsSql .= " ORDER BY h.datesve DESC";
    
    // 执行总支付金额查询
    $totalPaymentsStmt = $pdo->prepare($totalPaymentsSql);
    if ($dateDebut && $dateFin) {
        $totalPaymentsStmt->bindParam(':dateDebut', $dateDebut);
        $totalPaymentsStmt->bindParam(':dateFin', $dateFin);
    }
    $totalPaymentsStmt->execute();
    $totalPayments = $totalPaymentsStmt->fetch()['total'] ?? 0;
    
    // 执行教师数量查询
    $totalTeachersStmt = $pdo->prepare($totalTeachersSql);
    if ($dateDebut && $dateFin) {
        $totalTeachersStmt->bindParam(':dateDebut', $dateDebut);
        $totalTeachersStmt->bindParam(':dateFin', $dateFin);
    }
    $totalTeachersStmt->execute();
    $totalTeachers = $totalTeachersStmt->fetch()['total'] ?? 0;
    
    // 执行课程数量查询
    $totalCoursesStmt = $pdo->prepare($totalCoursesSql);
    if ($dateDebut && $dateFin) {
        $totalCoursesStmt->bindParam(':dateDebut', $dateDebut);
        $totalCoursesStmt->bindParam(':dateFin', $dateFin);
    }
    $totalCoursesStmt->execute();
    $totalCourses = $totalCoursesStmt->fetch()['total'] ?? 0;
    
    // 执行教师支付详情查询
    $teacherPaymentsStmt = $pdo->prepare($teacherPaymentsSql);
    if ($dateDebut && $dateFin) {
        $teacherPaymentsStmt->bindParam(':dateDebut', $dateDebut);
        $teacherPaymentsStmt->bindParam(':dateFin', $dateFin);
    }
    $teacherPaymentsStmt->execute();
    $teacherPayments = $teacherPaymentsStmt->fetchAll();
    
    // 准备返回数据
    $reportData = array(
        'summary' => array(
            'total_payments' => $totalPayments,
            'total_teachers' => $totalTeachers,
            'total_courses' => $totalCourses
        ),
        'details' => $teacherPayments
    );
    
    header('Content-Type: application/json');
    echo json_encode($reportData);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('error' => 'Erreur lors de la génération du rapport: ' . $e->getMessage()));
}
?>