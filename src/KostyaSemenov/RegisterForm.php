<?php

namespace KostyaSemenov;
use KostyaSemenov\RPR;
use pocketmine\Player;

class RegisterForm{
	
    private $plug;
    function __construct(RPR $plug){
        $this->plug = $plug;
    }
    function open(Player $pl){
        $f = $this->plug->form->createCustomForm(function (Player $pl, $data){
			if($data[0] !== NULL && $data[1] !== NULL){
					//data[0] - ник, data[1] - пароль
					$cfg = $this->plug->cfg->getAll();
					$all = $this->plug->all->getAll();
						$number = $this->plug->getNumberAccount($data[0]);
						if($number == NULL){
							$pl->sendMessage("§l§aВы успешно зарегистрировались!");
							$all["all"] = $all["all"] + 1;
							$this->plug->all->setAll($all);
							$this->plug->all->save();
							$cfg[$all["all"]]["nick"] = $data[0];
							$cfg[$all["all"]]["password"] = $data[1];
							$this->plug->cfg->setAll($cfg);
							$this->plug->cfg->save();
							$pl->sendMessage("§aВы успешно зарегистрировались!");
							$pl->setDisplayName($data[0]);
							$pl->setNameTag($data[0]);
							$pl->setImmobile(false);
						} else $pl->kick("Такой аккаунт зарегистрирован!");
			} else $this->open($pl);
        });
        $f->setTitle("Зарегистрируйтесь");
        $f->addInput("Ник", "Например: Ivan_Ivanov");
        $f->addInput("Пароль", "Пароль к аккаунту");
        $f->sendToPlayer($pl);
        return $f;
    }
}
