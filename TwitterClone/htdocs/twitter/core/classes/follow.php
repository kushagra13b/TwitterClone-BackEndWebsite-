<?php
class Follow extends User
{

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function checkFollow($followerID, $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `follow` WHERE `sender` = :user_id AND `reciever` = :followerID");
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":followerID", $followerID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function followBtn($profileID, $user_id, $followID){
        $data = $this->checkFollow($profileID, $user_id);
        if($this->loggedIn()===true){
            if($profileID != $user_id){
                if(isset($data['reciever']) && $data['reciever'] === $profileID){
                    //Following btn
                    return "<button class='f-btn following-btn follow-btn' data-follow='".$profileID."' data-profile='".$followID."' data-user_id='".$user_id."'>Following</button>";
                }else{
                    //Follow button
                    return "<button class='f-btn follow-btn' data-follow='".$profileID."' data-profile='".$followID."' data-user_id='".$user_id."'><i class='fa fa-user-plus'></i>Follow</button>";
                }
            }else{
                //edit button
                return "<button class='f-btn' onclick=location.href='".BASE_URL."profileEdit.php'>Edit Profile</button>";
            }
        }else{
            return "<button class='f-btn' onclick=location.href='".BASE_URL."index.php'><i class='fa fa-user-plus'></i>Follow</button>";
        }
    }

    function followAction($profileID, $user_id, $followID){

        if(!$this->checkFollow($profileID, $user_id)){

            $sql = "INSERT INTO `follow` (`sender`, `reciever`) VALUES (:sender, :reciever)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array(
                ':sender' => $user_id,
                ':reciever' => $profileID
            ));

        }else{

            $sql = "DELETE FROM `follow` WHERE `sender` = :sender AND `reciever` = :reciever";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array(
                ':sender' => $user_id,
                ':reciever' => $profileID
            ));
        }

        echo $this->followBtn($profileID, $user_id, $followID);
    }
}
?>