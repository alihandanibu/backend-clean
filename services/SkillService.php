<?php
namespace app\services;

use app\dao\SkillDAO;

class SkillService {
    private $skillDAO;

    public function __construct() {
        $this->skillDAO = new SkillDAO();
    }

    public function addSkill($userId, $data) {
        if (empty($data['name']) || empty($data['level'])) {
            return ['success' => false, 'message' => 'Skill name and level are required'];
        }

        $skillData = [
            'user_id' => $userId,
            'name' => $data['name'],
            'level' => $data['level'],
            'category' => $data['category'] ?? 'general'
        ];

        $skillId = $this->skillDAO->create($skillData);
        
        if ($skillId) {
            return ['success' => true, 'message' => 'Skill added successfully', 'skill_id' => $skillId];
        }

        return ['success' => false, 'message' => 'Failed to add skill'];
    }

    public function getSkillsByUser($userId) {
        $skills = $this->skillDAO->getByUserId($userId);
        
        if (empty($skills)) {
            return ['success' => true, 'skills' => []];
        }

        return ['success' => true, 'skills' => $skills];
    }

    public function updateSkill($userId, $skillId, $data) {
        $skill = $this->skillDAO->read($skillId);
        
        if (!$skill || $skill['user_id'] != $userId) {
            return ['success' => false, 'message' => 'Skill not found'];
        }

        $success = $this->skillDAO->update($skillId, $data);
        
        if ($success) {
            return ['success' => true, 'message' => 'Skill updated successfully'];
        }

        return ['success' => false, 'message' => 'Update failed'];
    }

    public function deleteSkill($userId, $skillId) {
        $skill = $this->skillDAO->read($skillId);
        
        if (!$skill || $skill['user_id'] != $userId) {
            return ['success' => false, 'message' => 'Skill not found'];
        }

        $success = $this->skillDAO->delete($skillId);
        
        if ($success) {
            return ['success' => true, 'message' => 'Skill deleted successfully'];
        }

        return ['success' => false, 'message' => 'Delete failed'];
    }
}
?>
