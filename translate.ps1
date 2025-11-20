# Translation Script - French to English
$ErrorActionPreference = "Stop"

$files = @(
    "resources\js\Pages\Order\Confirmation.vue",
    "resources\js\Pages\Order\Index.vue",  
    "resources\js\Pages\Order\Show.vue",
    "resources\js\Pages\Checkout\Index.vue"
)

# Translation map
$translations = @(
    @{old='title="Commande confirmée"'; new='title="Order Confirmed"'},
    @{old='Commande confirmée !'; new='Order Confirmed!'},
    @{old='Merci pour votre commande'; new='Thank you for your order'},
    @{old='Numéro de commande:'; new='Order number:'},
    @{old='Détails de la commande'; new='Order Details'},
    @{old='Adresse de livraison'; new='Shipping Address'},
    @{old='Tél:'; new='Tel:'},
    @{old='Mode de paiement'; new='Payment Method'},
    @{old='Paiement à la livraison'; new='Cash on Delivery'},
    @{old='Carte bancaire'; new='Credit Card'},
    @{old='Statut:'; new='Status:'},
    @{old='Payé'; new='Paid'},
    @{old='En attente'; new='Pending'},
    @{old='Articles commandés'; new='Ordered Items'},
    @{old='Prix unitaire:'; new='Unit price:'},
    @{old='Quantité:'; new='Quantity:'},
    @{old='Sous-total'; new='Subtotal'},
    @{old='TVA'; new='Tax'},
    @{old='Livraison'; new='Shipping'},
    @{old='Gratuite'; new='Free'},
    @{old='Total'; new='Total'},
    @{old='Voir les détails'; new='View Details'},
    @{old='Imprimer le reçu'; new='Print Receipt'},
    @{old='Continuer mes achats'; new='Continue Shopping'},
    @{old='Et maintenant ?'; new='What''s Next?'},
    @{old='Nous préparons votre commande'; new='We are preparing your order'},
    @{old='Vous recevrez un email de confirmation'; new='You will receive a confirmation email'},
    @{old='Suivez votre commande depuis votre espace client'; new='Track your order from your account'},
    @{old='Livraison estimée :'; new='Estimated delivery:'},
    @{old='jours ouvrables'; new='business days'},
    @{old='title="Mes commandes"'; new='title="My Orders"'},
    @{old='Mes commandes'; new='My Orders'},
    @{old='Toutes mes commandes'; new='All my orders'},
    @{old='Toutes'; new='All'},
    @{old='En cours de traitement'; new='Processing'},
    @{old='Expédiée'; new='Shipped'},
    @{old='Livrée'; new='Delivered'},
    @{old='Annulée'; new='Cancelled'},
    @{old='Aucune commande'; new='No orders'},
    @{old='Vous n''avez pas encore passé de commande'; new='You haven''t placed any orders yet'},
    @{old='Commencer mes achats'; new='Start Shopping'},
    @{old='commande du'; new='order from'},
    @{old='article'; new='item'},
    @{old='articles'; new='items'},
    @{old='Commande'; new='Order'},
    @{old='Passée le'; new='Placed on'},
    @{old='Retour à mes commandes'; new='Back to orders'},
    @{old='Annuler la commande'; new='Cancel Order'},
    @{old='Annulation...'; new='Cancelling...'},
    @{old='Suivi de commande'; new='Order Tracking'},
    @{old='Commande passée'; new='Order Placed'},
    @{old='En préparation'; new='Preparing'},
    @{old='Note'; new='Note'},
    @{old='Votre commande a été annulée'; new='Your order has been cancelled'},
    @{old='Êtes-vous sûr de vouloir annuler cette commande ?'; new='Are you sure you want to cancel this order?'},
    @{old='Récapitulatif'; new='Order Summary'},
    @{old='image_path \|\| ''https://via.placeholder.com'; new='getDemoImage(item)'},
    @{old=':src="item.product?.image_path \|\| ''https://via.placeholder.com/100''"'; new=':src="getDemoImage(item)"'},
    @{old=':src="item.product?.image_path \|\| ''https://via.placeholder.com/60''"'; new=':src="getDemoImage(item)"'}
)

$count = 0
foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "Translating $file..."
        $content = Get-Content $file -Raw -Encoding UTF8
        
        foreach ($trans in $translations) {
            $content = $content.Replace($trans.old, $trans.new)
        }
        
        # Add getDemoImage helper if not exists and needed
        if ($file -like "*Order*" -and $content -notlike "*getDemoImage*") {
            $helper = @"

const getDemoImage = (item) => {
    const name = item.product_name || item.name || 'Product';
    return ``https://via.placeholder.com/80/4F46E5/FFFFFF?text=$\{encodeURIComponent(name.substring(0, 10))}``;
};
"@
            $content = $content.Replace("const props = defineProps({", $helper + "`r`n`r`nconst props = defineProps({")
        }
        
        $content | Set-Content $file -Encoding UTF8 -NoNewline
        $count++
    }
}

Write-Host "`nTranslated $count files successfully!" -ForegroundColor Green
