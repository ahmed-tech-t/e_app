enum PriceType {
  retail('retail'),
  wholesale('wholesale');

  final String value;
  const PriceType(this.value);

  static PriceType fromString(String? value) {
    return PriceType.values.firstWhere(
      (type) => type.value == value,
      orElse: () => PriceType.retail,
    );
  }
}

