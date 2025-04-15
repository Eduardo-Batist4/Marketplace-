<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Status da Compra</title>
  </head>
  <body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f6f6f6;">
    <table align="center" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; margin-top: 30px; border-radius: 8px; overflow: hidden;">
      <tr>
        <td style="padding: 20px; text-align: center; background-color: #2c3e50; color: white;">
          <h2 style="margin: 0;">📦 Status da sua compra</h2>
        </td>
      </tr>

      <!-- Status: Pending -->
      @if ($order->status == 'pending')
      <tr>
        <td style="padding: 20px; border-bottom: 1px solid #eee;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td width="60" valign="top">
                <img src="https://img.icons8.com/color/48/clock--v1.png" alt="Pending" style="display:block;" />
              </td>
              <td style="padding-left: 10px;">
                <h3 style="margin: 0; color: #e67e22;">Pendente</h3>
                <p style="margin: 5px 0 0 0;">Estamos aguardando a confirmação do pagamento.</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Status: Processing -->
      @elseif ($order->status == 'processing')
      <tr>
        <td style="padding: 20px; border-bottom: 1px solid #eee;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td width="60" valign="top">
                <img src="https://img.icons8.com/color/48/settings--v1.png" alt="Processing" style="display:block;" />
              </td>
              <td style="padding-left: 10px;">
                <h3 style="margin: 0; color: #3498db;">Processando</h3>
                <p style="margin: 5px 0 0 0;">Seu pedido está sendo preparado com carinho 🛠️</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Status: Shipped -->
      @elseif ($order->status == 'shipped')
      <tr>
        <td style="padding: 20px; border-bottom: 1px solid #eee;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td width="60" valign="top">
                <img src="https://img.icons8.com/color/48/delivery.png" alt="Shipped" style="display:block;" />
              </td>
              <td style="padding-left: 10px;">
                <h3 style="margin: 0; color: #f1c40f;">Enviado</h3>
                <p style="margin: 5px 0 0 0;">Seu pedido saiu para entrega e logo estará com você.</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      @elseif ($order->status == 'completed')
      <tr>
        <td style="padding: 20px;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td width="60" valign="top">
                <img src="https://img.icons8.com/color/48/checked--v1.png" alt="Completed" style="display:block;" />
              </td>
              <td style="padding-left: 10px;">
                <h3 style="margin: 0; color: #2ecc71;">Entregue</h3>
                <p style="margin: 5px 0 0 0;">Compra finalizada com sucesso. Obrigado por escolher a gente! 💚</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      @else
      @endif
      <!-- Footer -->
      <tr>
        <td style="padding: 20px; text-align: center; font-size: 12px; color: #999;">
          Se você tiver alguma dúvida, entre em contato com nosso suporte.
        </td>
      </tr>
    </table>
  </body>
</html>
