<?php
namespace app\services;

use app\dao\ProjectDAO;

class ProjectService {
    private $projectDAO;

    public function __construct() {
        $this->projectDAO = new ProjectDAO();
    }

    public function addProject($userId, $data) {
        if (empty($data['title'])) {
            return ['success' => false, 'message' => 'Project title is required'];
        }

        $projectData = [
            'user_id' => $userId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'technologies' => $data['technologies'] ?? null,
            'link' => $data['link'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null
        ];

        $projectId = $this->projectDAO->create($projectData);
        
        if ($projectId) {
            return ['success' => true, 'message' => 'Project added successfully', 'project_id' => $projectId];
        }

        return ['success' => false, 'message' => 'Failed to add project'];
    }

    public function getProjectsByUser($userId) {
        $projects = $this->projectDAO->getByUserId($userId);
        
        if (empty($projects)) {
            return ['success' => true, 'projects' => []];
        }

        return ['success' => true, 'projects' => $projects];
    }

    public function updateProject($userId, $projectId, $data) {
        $project = $this->projectDAO->read($projectId);
        
        if (!$project || $project['user_id'] != $userId) {
            return ['success' => false, 'message' => 'Project not found'];
        }

        $success = $this->projectDAO->update($projectId, $data);
        
        if ($success) {
            return ['success' => true, 'message' => 'Project updated successfully'];
        }

        return ['success' => false, 'message' => 'Update failed'];
    }

    public function deleteProject($userId, $projectId) {
        $project = $this->projectDAO->read($projectId);
        
        if (!$project || $project['user_id'] != $userId) {
            return ['success' => false, 'message' => 'Project not found'];
        }

        $success = $this->projectDAO->delete($projectId);
        
        if ($success) {
            return ['success' => true, 'message' => 'Project deleted successfully'];
        }

        return ['success' => false, 'message' => 'Delete failed'];
    }
}
?>
