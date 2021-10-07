<?php
/**
 * Class LabelPrint.
 *
 * @package Packetery\Order
 */

declare( strict_types=1 );

namespace Packetery\Module\Order;

use Packetery\Core\Api\Soap\Client;
use Packetery\Core\Api\Soap\Request;
use Packetery\Core\Api\Soap\Response;
use Packetery\Module\FormFactory;
use Packetery\Module\MessageManager;
use Packetery\Module\Options\Provider;
use PacketeryLatte\Engine;
use PacketeryNette\Forms\Form;
use PacketeryNette\Http;

/**
 * Class LabelPrint.
 *
 * @package Packetery\Order
 */
class LabelPrint {
	/**
	 * PacketeryLatte Engine.
	 *
	 * @var Engine PacketeryLatte engine.
	 */
	private $latteEngine;

	/**
	 * Options Provider
	 *
	 * @var Provider
	 */
	private $optionsProvider;

	/**
	 * Form factory.
	 *
	 * @var FormFactory Form factory.
	 */
	private $formFactory;

	/**
	 * Http Request.
	 *
	 * @var Http\Request
	 */
	private $httpRequest;

	/**
	 * SOAP API Client.
	 *
	 * @var Client SOAP API Client.
	 */
	private $soapApiClient;

	/**
	 * Message Manager.
	 *
	 * @var MessageManager
	 */
	private $messageManager;

	/**
	 * Order repository.
	 *
	 * @var Repository
	 */
	private $orderRepository;

	/**
	 * LabelPrint constructor.
	 *
	 * @param Engine         $latteEngine Latte Engine.
	 * @param Provider       $optionsProvider Options provider.
	 * @param FormFactory    $formFactory Form factory.
	 * @param Http\Request   $httpRequest Http Request.
	 * @param Client         $soapApiClient SOAP API Client.
	 * @param MessageManager $messageManager Message Manager.
	 * @param Repository     $orderRepository Order repository.
	 */
	public function __construct(
		Engine $latteEngine,
		Provider $optionsProvider,
		FormFactory $formFactory,
		Http\Request $httpRequest,
		Client $soapApiClient,
		MessageManager $messageManager,
		Repository $orderRepository
	) {
		$this->latteEngine     = $latteEngine;
		$this->optionsProvider = $optionsProvider;
		$this->formFactory     = $formFactory;
		$this->httpRequest     = $httpRequest;
		$this->soapApiClient   = $soapApiClient;
		$this->messageManager  = $messageManager;
		$this->orderRepository = $orderRepository;
	}

	/**
	 * Generates id of transient to store order ids.
	 *
	 * @return string
	 */
	public static function getOrderIdsTransientName(): string {
		return 'packetery_label_print_order_ids_' . get_current_user_id();
	}

	/**
	 * Prepares form and renders template.
	 */
	public function render(): void {
		$form = $this->createForm( $this->optionsProvider->getLabelMaxOffset( $this->getLabelFormat() ) );
		$this->latteEngine->render(
			PACKETERY_PLUGIN_DIR . '/template/order/label-print.latte',
			[ 'form' => $form ]
		);
	}

	/**
	 * Gets label format for current job.
	 *
	 * @return string
	 */
	private function getLabelFormat(): string {
		$packetaLabelFormat = ( $this->optionsProvider->get_packeta_label_format() ?? '' );
		$carrierLabelFormat = ( $this->optionsProvider->get_carrier_label_format() ?? '' );

		return ( $this->httpRequest->getQuery( 'label_type' ) === 'print_carrier_labels' ? $carrierLabelFormat : $packetaLabelFormat );
	}

	/**
	 * Outputs pdf.
	 */
	public function outputLabelsPdf(): void {
		if ( $this->httpRequest->getQuery( 'page' ) !== 'label-print' ) {
			return;
		}
		if ( ! get_transient( self::getOrderIdsTransientName() ) ) {
			$this->messageManager->flash_message( __( 'noOrdersSelected', 'packetery' ), 'info' );

			return;
		}
		$maxOffset = $this->optionsProvider->getLabelMaxOffset( $this->getLabelFormat() );
		$form      = $this->createForm( $maxOffset );
		if ( 0 === $maxOffset ) {
			$offset = 0;
		} elseif ( $form->isSubmitted() ) {
			$data   = $form->getValues( 'array' );
			$offset = $data['offset'];
		} else {
			return;
		}

		$response = $this->requestLabels( $offset );
		delete_transient( self::getOrderIdsTransientName() );
		if ( ! $response || $response->getFaultString() ) {
			$message = ( null !== $response && $response->getFaultString() ) ?
				__( 'labelPrintFailedMoreInfoInLog', 'packetery' ) :
				__( 'youSelectedOrdersThatWereNotSubmitted', 'packetery' );
			$this->messageManager->flash_message( $message, 'error' );
			if ( wp_safe_redirect( 'edit.php?post_type=shop_order' ) ) {
				exit;
			}
		}

		header( 'Content-Type: application/pdf' );
		header( 'Content-Transfer-Encoding: Binary' );
		header( 'Content-Length: ' . strlen( $response->getPdfContents() ) );
		header( 'Content-Disposition: attachment; filename="' . $this->getFilename() . '"' );
		// @codingStandardsIgnoreStart
		echo $response->getPdfContents();
		// @codingStandardsIgnoreEnd
		exit;
	}

	/**
	 * Creates offset setting form.
	 *
	 * @param int $maxOffset Maximal offset.
	 *
	 * @return Form
	 */
	public function createForm( int $maxOffset ): Form {
		$form = $this->formFactory->create();
		$form->setAction( $this->httpRequest->getUrl() );

		$availableOffsets = [];
		for ( $i = 0; $i <= $maxOffset; $i ++ ) {
			// translators: %s is offset.
			$availableOffsets[ $i ] = ( 0 === $i ? __( 'dontSkipAnyField', 'packetery' ) : sprintf( __( 'skip%sFields', 'packetery' ), $i ) );
		}
		$form->addSelect(
			'offset',
			__( 'labelsOffset', 'packetery' ),
			$availableOffsets
		)->checkDefaultValue( false );

		return $form;
	}

	/**
	 * Registers submenu item.
	 */
	public function register(): void {
		add_submenu_page(
			'packeta-options',
			__( 'printLabels', 'packetery' ),
			__( 'printLabels', 'packetery' ),
			'manage_options',
			'label-print',
			array(
				$this,
				'render',
			),
			20
		);
	}

	/**
	 * Hides submenu item.
	 */
	public function hideFromMenus(): void {
		global $submenu;
		if ( isset( $submenu['packeta-options'] ) ) {
			foreach ( $submenu['packeta-options'] as $key => $menu ) {
				if ( 'label-print' === $menu[2] ) {
					unset( $submenu['packeta-options'][ $key ] );
				}
			}
		}
	}

	/**
	 * Prepares labels.
	 *
	 * @param int $offset Offset value.
	 *
	 * @return Response\PacketsLabelsPdf|null
	 */
	private function requestLabels( int $offset ): ?Response\PacketsLabelsPdf {
		$isCarrierLabels = ( $this->httpRequest->getQuery( 'label_type' ) === 'print_carrier_labels' );
		$packetIds       = $this->getPacketIds( $isCarrierLabels );
		if ( ! $packetIds ) {
			return null;
		}

		if ( $isCarrierLabels ) {
			$carrierPacketNumbers = $this->getCarrierPacketNumbers( $packetIds );
			$request              = new Request\PacketsCourierLabelsPdf( array_values( $carrierPacketNumbers ), $this->getLabelFormat(), $offset );
			$response             = $this->soapApiClient->packetsCarrierLabelsPdf( $request );
		} else {
			$request  = new Request\PacketsLabelsPdf( array_values( $packetIds ), $this->getLabelFormat(), $offset );
			$response = $this->soapApiClient->packetsLabelsPdf( $request );
		}

		if ( ! $response->getFaultString() ) {
			foreach ( array_keys( $packetIds ) as $orderId ) {
				update_post_meta( $orderId, Entity::META_IS_LABEL_PRINTED, true );
				if ( $isCarrierLabels ) {
					update_post_meta( $orderId, Entity::META_CARRIER_NUMBER, $carrierPacketNumbers[ $orderId ]['courierNumber'] );
				}
			}
		}

		return $response;
	}

	/**
	 * Gets filename for label pdf.
	 *
	 * @return string
	 */
	private function getFilename(): string {
		return 'packeta_labels_' . strtolower( str_replace( ' ', '_', $this->getLabelFormat() ) ) . '.pdf';
	}

	/**
	 * Gets saved packet ids.
	 *
	 * @param bool $isCarrierLabels Are carrier labels requested?.
	 *
	 * @return array
	 */
	private function getPacketIds( bool $isCarrierLabels ): array {
		$orderIds  = get_transient( self::getOrderIdsTransientName() );
		$orders    = $this->orderRepository->getOrdersByIds( $orderIds );
		$packetIds = [];
		foreach ( $orders as $order ) {
			if ( null === $order->getPacketId() ) {
				continue;
			}
			if ( ! $isCarrierLabels || $order->isExternalCarrier() ) {
				$packetIds[ $order->getPostId() ] = $order->getPacketId();
			}
		}

		return $packetIds;
	}

	/**
	 * Gets carrier packet numbers from API.
	 *
	 * @param array $packetIds List of packet ids.
	 *
	 * @return array
	 */
	private function getCarrierPacketNumbers( array $packetIds ): array {
		$carrierPacketNumbers = [];
		foreach ( $packetIds as $orderId => $packetId ) {
			$numberRequest  = new Request\PacketCourierNumber( $packetId );
			$numberResponse = $this->soapApiClient->packetCourierNumber( $numberRequest );
			if ( $numberResponse->getFaultString() ) {
				continue;
			}
			$carrierPacketNumbers[ $orderId ] = [
				'packetId'      => $packetId,
				'courierNumber' => $numberResponse->getNumber(),
			];
		}

		return $carrierPacketNumbers;
	}

}
