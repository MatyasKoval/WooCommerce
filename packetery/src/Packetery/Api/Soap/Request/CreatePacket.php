<?php
/**
 * Class CreatePacket.
 *
 * @package Packetery\Api\Soap\Request
 */

namespace Packetery\Api\Soap\Request;

/**
 * Class CreatePacket.
 *
 * @package Packetery\Api\Soap\Request
 */
class CreatePacket {

	/**
	 * Order id.
	 *
	 * @var string
	 */
	private $number;

	/**
	 * Customer name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Customer surname.
	 *
	 * @var string
	 */
	private $surname;

	/**
	 * Customer e-mail.
	 *
	 * @var string
	 */
	private $email;

	/**
	 * Customer phone.
	 *
	 * @var string
	 */
	private $phone;

	/**
	 * Pickup point or carrier id.
	 *
	 * @var string
	 */
	private $addressId;

	/**
	 * Order value.
	 *
	 * @var float
	 */
	private $value;

	/**
	 * Sender label.
	 *
	 * @var string
	 */
	private $eshop;

	/**
	 * Package weight.
	 *
	 * @var float
	 */
	private $weight;

	/**
	 * Customer street for address delivery.
	 *
	 * @var string
	 */
	private $street;

	/**
	 * Customer city for address delivery.
	 *
	 * @var string
	 */
	private $city;

	/**
	 * Customer zip for address delivery.
	 *
	 * @var string
	 */
	private $zip;

	/**
	 * Cash on delivery value.
	 *
	 * @var float
	 */
	private $cod;

	/**
	 * Carrier pickup point.
	 *
	 * @var string
	 */
	private $carrierPickupPoint;

	/**
	 * Package size.
	 *
	 * @var array
	 */
	private $size;

	/**
	 * Gets all properties as array.
	 *
	 * @return array
	 */
	public function __toArray(): array {
		return get_object_vars( $this );
	}

	/**
	 * Gets submittable data.
	 *
	 * @return array
	 */
	public function getSubmittableData(): array {
		return array_filter( $this->__toArray() );
	}

	/**
	 * Sets number.
	 *
	 * @param string $number Number.
	 */
	public function setNumber( string $number ): void {
		$this->number = $number;
	}

	/**
	 * Sets phone.
	 *
	 * @param string $name Name.
	 */
	public function setName( string $name ): void {
		$this->name = $name;
	}

	/**
	 * Sets surname.
	 *
	 * @param string $surname Surname.
	 */
	public function setSurname( string $surname ): void {
		$this->surname = $surname;
	}

	/**
	 * Sets e-mail.
	 *
	 * @param string $email E-mail.
	 */
	public function setEmail( string $email ): void {
		$this->email = $email;
	}

	/**
	 * Sets phone.
	 *
	 * @param string $phone Phone.
	 */
	public function setPhone( string $phone ): void {
		$this->phone = $phone;
	}

	/**
	 * Sets address id.
	 *
	 * @param string $addressId Address id.
	 */
	public function setAddressId( string $addressId ): void {
		$this->addressId = $addressId;
	}

	/**
	 * Sets value.
	 *
	 * @param float $value Value.
	 */
	public function setValue( float $value ): void {
		$this->value = $value;
	}

	/**
	 * Sets sender label.
	 *
	 * @param string $eshop Sender label.
	 */
	public function setEshop( string $eshop ): void {
		$this->eshop = $eshop;
	}

	/**
	 * Sets weight.
	 *
	 * @param float $weight Weight.
	 */
	public function setWeight( float $weight ): void {
		$this->weight = $weight;
	}

	/**
	 * Sets street.
	 *
	 * @param string $street Street.
	 */
	public function setStreet( string $street ): void {
		$this->street = $street;
	}

	/**
	 * Sets city.
	 *
	 * @param string $city City.
	 */
	public function setCity( string $city ): void {
		$this->city = $city;
	}

	/**
	 * Sets zip.
	 *
	 * @param string $zip Zip.
	 */
	public function setZip( string $zip ): void {
		$this->zip = $zip;
	}

	/**
	 * Sets COD.
	 *
	 * @param float $cod COD.
	 */
	public function setCod( float $cod ): void {
		$this->cod = $cod;
	}

	/**
	 * Sets carrier pickup point.
	 *
	 * @param string $carrierPickupPoint Carrier pickup point.
	 */
	public function setCarrierPickupPoint( string $carrierPickupPoint ): void {
		$this->carrierPickupPoint = $carrierPickupPoint;
	}

	/**
	 * Sets size.
	 *
	 * @param float $length Packet length.
	 * @param float $width Packet width.
	 * @param float $height Packet height.
	 */
	public function setSize( float $length, float $width, float $height ): void {
		$this->size = [
			'length' => $length,
			'width'  => $width,
			'height' => $height,
		];
	}
}
