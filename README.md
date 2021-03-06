# inResto'ck

## introduction
Ce site a été créé lors de mon projet de fin de formation à l'école O'clock. Il a été réalisé à deux, en Symfony, en 1 mois et présenté à l'issue de cette période.

L’idée de départ de ce projet est de proposer une solution pratique et interactive pour permettre une gestion des stocks optimisée dans la restauration.

Un des élèves de l'école a travaillé dans un restaurant servant 200 à 300 couverts par jour et comportant plusieurs postes tels que la pizzeria, la pâtisserie ou la restauration traditionnelle. Il a constaté plusieurs problèmes relatifs à cette gestion des stocks.

Pour chaque poste, on intervient sur les stocks que ce soit lors de la mise en place du service ou pendant celui-ci. Voici le déroulement courant de cette opération : un salarié du restaurant se rend dans la réserve pour prendre un ou plusieurs produits en fonction des besoins en cuisine. Il prévient son responsable de ce qu’il a pris et celui-ci doit le noter pour en garder une trace et pouvoir mettre à jour les stocks par la suite.

Plusieurs problèmes se posent alors :
La pression du service et la vitesse d’exécution des actions entraînent des oublis de signalement ou des erreurs d’enregistrement. Les stocks doivent donc être vérifiés fréquemment, à la fin du service, tard le soir parfois ce qui engendre des couts de main d’œuvre supplémentaires et de la fatigue chez le personnel.
D’autre part, les erreurs de gestion de stocks vont avoir un impact sur la suite du fonctionnement du restaurant. Les commandes seront faites avec du retard, potentiellement de façon incomplète. Les livraisons risqueront d’arriver trop tard, occasionnant des ruptures de stock et parfois, l’impossibilité de réaliser certaines recettes prévues à la carte.
Même si l’inventaire reste une opération indispensable, la fréquence peut en être réduite par une meilleure gestion des prélèvements effectués.

C’est dans cet objectif d’apporter une solution concrète de gestion informatique que nous avons conçu le site inResto'ck.

## Vue d'ensemble
Le site inResto'ck est donc destiné à faciliter la gestion des stocks des restaurants en permettant aux employés de modifier les stocks informatisés, en temps réel, y compris pendant le service.

Lorsqu’un employé sort un produit des réserves pour l’apporter en cuisine ou en salle, il lui suffit de  se connecter sur le site, de chercher le produit correspondant au moyen d’un tri par catégories et de le mettre en sortie en indiquant la quantité prélevée.

Le site permet également la mise à jour des stocks pour cause d’inventaire, par des corrections négatives ou positives, mais également pour réapprovisionnement suite à la livraison des commandes.
Chaque modification apportée est enregistrée en base de données sous forme d’une ligne d’historique des modifications. L’ensemble de cet historique est accessible à l’administrateur qui est ainsi au courant en temps réel de l’état des stocks. D’autre part, quand la quantité disponible d’un produit atteint une limite critique, l’application envoie un mail d’alerte au gestionnaire, lui permettant ainsi de penser à préparer ses commandes.

L’usage de l’historique ne s’arrête pas là. L’idée est aussi , dans une version ultérieure, de l’utiliser pour faire des statistiques. On pourra récupérer les informations concernant un produit particulier : le rythme ou l’heure des prélèvements, la périodicité des renouvellements du stock, quel poste l’utilise le plus. Un tri des modifications par poste apportera des informations intéressantes sur son fonctionnement. On pourra également noter l’activité des employés, savoir qui va le plus souvent dans la réserve et avoir ainsi une vision du fonctionnement des équipes.

Pour ce qui est de la gestion du restaurant, le site permet également à l’administrateur de gérer l’ensemble de ses données : salariés, fournisseurs, postes, produits, catégories. Il dispose d’un back-end lui permettant de les créer, les afficher, les modifier ou les supprimer.

