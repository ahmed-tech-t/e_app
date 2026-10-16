import 'package:flutter/material.dart';

class CustomTextFormField extends StatelessWidget {
  final TextEditingController? controller;
  final String? label;
  final String? hintText;
  final Widget? prefixIcon;
  final Widget? suffixIcon;
  final String? Function(String?)? validator;
  final TextInputType? keyboardType;
  final bool readOnly;
  final bool autofocus;
  final ValueChanged<String>? onChanged;
  final ValueChanged<String>? onFieldSubmitted;
  final int maxLines;
  final FocusNode? focusNode;

  const CustomTextFormField({
    super.key,
    this.controller,
    this.label,
    this.hintText,
    this.prefixIcon,
    this.suffixIcon,
    this.validator,
    this.keyboardType,
    this.readOnly = false,
    this.autofocus = false,
    this.onChanged,
    this.onFieldSubmitted,
    this.maxLines = 1,
    this.focusNode,
  });

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      controller: controller,
      focusNode: focusNode,
      keyboardType: keyboardType,
      readOnly: readOnly,
      autofocus: autofocus,
      validator: validator,
      onChanged: onChanged,
      onFieldSubmitted: onFieldSubmitted,
      maxLines: maxLines,
      decoration: InputDecoration(
        labelText: label,
        hintText: hintText,
        prefixIcon: prefixIcon,
        suffixIcon: suffixIcon,
      ),
    );
  }
}

/// Widget Preview
Widget previewCustomTextFormField() {
  return const MaterialApp(
    home: Scaffold(
      body: Center(
        child: Padding(
          padding: EdgeInsets.all(16.0),
          child: CustomTextFormField(
            label: 'Customer Name',
            hintText: 'e.g. John Doe',
            prefixIcon: Icon(Icons.person_outline),
          ),
        ),
      ),
    ),
  );
}
