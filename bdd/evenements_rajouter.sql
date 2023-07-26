-- Mise à jour le statut des demandes expirées
UPDATE Demandes_acces
SET Statut = 'Expiré'
WHERE Date_expiration <= NOW()



-- Mettre à jour des capacité des ressources
UPDATE Ressources r
SET Capacite_actuelle = r.Capacite_totale - (
SELECT COUNT(*) FROM Demandes_acces d
INNER JOIN Demandes_acces_ressources dr ON d.Id_demande = dr.Id_demande
WHERE dr.Id_ressource = r.Id_ressource 
AND d.Statut = 'Validé')
