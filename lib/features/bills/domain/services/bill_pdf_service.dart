import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:printing/printing.dart';
import 'package:pos_mobile/features/bills/domain/models/bill.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';

class BillPdfService {
  static Future<Uint8List> generatePdf(Bill bill, {List<Product>? products}) async {
    final pdf = pw.Document();
    final font = await PdfGoogleFonts.cairoRegular();
    final fontBold = await PdfGoogleFonts.cairoBold();
    final dateFormat = DateFormat('yyyy-MM-dd hh:mm a');
    final formattedDate = dateFormat.format(DateTime.now());

    pdf.addPage(
      pw.Page(
        pageFormat: PdfPageFormat.a4,
        margin: const pw.EdgeInsets.all(32),
        build: (pw.Context context) {
          final items = bill.items;
          final total = bill.total ?? 0.0;
          final discount = bill.discount ?? 0.0;
          final tax = bill.tax ?? 15.0;
          final grandTotal = bill.grandTotal ?? total;

          return pw.Column(
            crossAxisAlignment: pw.CrossAxisAlignment.start,
            children: [
              // Header
              pw.Row(
                mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                children: [
                  pw.Column(
                    crossAxisAlignment: pw.CrossAxisAlignment.start,
                    children: [
                      pw.Text(
                        'POS INVOICE',
                        style: pw.TextStyle(
                          font: fontBold,
                          fontSize: 24,
                          color: PdfColors.blueGrey900,
                        ),
                      ),
                      pw.SizedBox(height: 4),
                      pw.Text(
                        bill.code != null && bill.code!.isNotEmpty
                            ? bill.code!
                            : 'Bill #${bill.id ?? '-'}',
                        style: pw.TextStyle(
                          font: fontBold,
                          fontSize: 14,
                          color: PdfColors.grey700,
                        ),
                      ),
                    ],
                  ),
                  pw.Column(
                    crossAxisAlignment: pw.CrossAxisAlignment.end,
                    children: [
                      pw.Text(
                        'Date: $formattedDate',
                        style: pw.TextStyle(font: font, fontSize: 10, color: PdfColors.grey700),
                      ),
                      pw.Text(
                        'Store #1',
                        style: pw.TextStyle(font: font, fontSize: 10, color: PdfColors.grey700),
                      ),
                    ],
                  ),
                ],
              ),
              pw.SizedBox(height: 16),
              pw.Divider(thickness: 1, color: PdfColors.grey400),
              pw.SizedBox(height: 8),

              // Customer Details
              pw.Container(
                padding: const pw.EdgeInsets.all(12),
                decoration: pw.BoxDecoration(
                  color: PdfColors.grey100,
                  borderRadius: pw.BorderRadius.circular(6),
                ),
                child: pw.Row(
                  mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                  children: [
                    pw.Column(
                      crossAxisAlignment: pw.CrossAxisAlignment.start,
                      children: [
                        pw.Text(
                          'Customer Name: ${bill.customerName ?? 'Walk-in'}',
                          style: pw.TextStyle(font: fontBold, fontSize: 12),
                        ),
                        if (bill.customerPhone != null && bill.customerPhone!.isNotEmpty) ...[
                          pw.SizedBox(height: 4),
                          pw.Text(
                            'Phone: ${bill.customerPhone}',
                            style: pw.TextStyle(font: font, fontSize: 10, color: PdfColors.grey800),
                          ),
                        ],
                      ],
                    ),
                    pw.Text(
                      'Total Items: ${items.length}',
                      style: pw.TextStyle(font: font, fontSize: 11, color: PdfColors.grey800),
                    ),
                  ],
                ),
              ),
              pw.SizedBox(height: 20),

              // Items Table
              pw.TableHelper.fromTextArray(
                headers: ['#', 'Item / Barcode', 'Qty', 'Unit Price', 'Total'],
                data: List<List<dynamic>>.generate(items.length, (index) {
                  final item = items[index];
                  final unitPrice = item.price ?? 0.0;
                  final itemTotal = item.total ?? (unitPrice * (item.quantity ?? 1));

                  Product? matched;
                  if (products != null && item.productId != null) {
                    for (final p in products) {
                      if (p.id == item.productId) {
                        matched = p;
                        break;
                      }
                    }
                  }

                  final name = matched != null
                      ? ((matched.nameEn?.isNotEmpty ?? false)
                          ? matched.nameEn!
                          : (matched.nameAr ?? 'Product #${item.productId ?? '-'}'))
                      : 'Product #${item.productId ?? '-'}';
                  final code = matched?.originalCode ?? matched?.code;
                  final itemLabel = code != null && code.isNotEmpty ? '$name\n($code)' : name;

                  return [
                    '${index + 1}',
                    itemLabel,
                    '${item.quantity ?? 1}',
                    '\$${unitPrice.toStringAsFixed(2)}',
                    '\$${itemTotal.toStringAsFixed(2)}',
                  ];
                }),
                headerStyle: pw.TextStyle(font: fontBold, fontSize: 11, color: PdfColors.white),
                headerDecoration: const pw.BoxDecoration(color: PdfColors.blueGrey800),
                cellStyle: pw.TextStyle(font: font, fontSize: 10),
                cellPadding: const pw.EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                cellAlignments: {
                  0: pw.Alignment.center,
                  1: pw.Alignment.centerLeft,
                  2: pw.Alignment.center,
                  3: pw.Alignment.centerRight,
                  4: pw.Alignment.centerRight,
                },
              ),
              pw.SizedBox(height: 20),

              // Financial Summary & QR Code
              pw.Row(
                mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                crossAxisAlignment: pw.CrossAxisAlignment.end,
                children: [
                  pw.Column(
                    crossAxisAlignment: pw.CrossAxisAlignment.start,
                    children: [
                      pw.Container(
                        height: 70,
                        width: 70,
                        child: pw.BarcodeWidget(
                          barcode: pw.Barcode.qrCode(),
                          data: bill.code ?? '${bill.id ?? ''}',
                          drawText: false,
                        ),
                      ),
                      pw.SizedBox(height: 4),
                      pw.Text(
                        bill.code ?? 'Bill #${bill.id ?? ''}',
                        style: pw.TextStyle(font: font, fontSize: 8, color: PdfColors.grey700),
                      ),
                    ],
                  ),
                  pw.Container(
                    width: 220,
                    child: pw.Column(
                      crossAxisAlignment: pw.CrossAxisAlignment.stretch,
                      children: [
                        _buildSummaryRow(font, 'Subtotal:', '\$${total.toStringAsFixed(2)}'),
                        pw.SizedBox(height: 4),
                        _buildSummaryRow(font, 'Discount:', '${discount.toStringAsFixed(1)}%'),
                        pw.SizedBox(height: 4),
                        _buildSummaryRow(font, 'Tax (${tax.toStringAsFixed(0)}%):', '\$${((total - (total * discount / 100)) * (tax / 100)).toStringAsFixed(2)}'),
                        pw.Divider(thickness: 1, color: PdfColors.grey400),
                        pw.Row(
                          mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                          children: [
                            pw.Text('Grand Total:', style: pw.TextStyle(font: fontBold, fontSize: 14)),
                            pw.Text('\$${grandTotal.toStringAsFixed(2)}', style: pw.TextStyle(font: fontBold, fontSize: 14, color: PdfColors.blue900)),
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              pw.Spacer(),

              // Footer Note
              pw.Center(
                child: pw.Text(
                  'Thank you for your business!',
                  style: pw.TextStyle(font: font, fontSize: 11, color: PdfColors.grey600),
                ),
              ),
            ],
          );
        },
      ),
    );

    return pdf.save();
  }

  static pw.Widget _buildSummaryRow(pw.Font font, String title, String value) {
    return pw.Row(
      mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
      children: [
        pw.Text(title, style: pw.TextStyle(font: font, fontSize: 10, color: PdfColors.grey700)),
        pw.Text(value, style: pw.TextStyle(font: font, fontSize: 10, color: PdfColors.grey900)),
      ],
    );
  }

  static Future<void> printOrPreviewReceipt(
    BuildContext context,
    Bill bill, {
    List<Product>? products,
  }) async {
    final pdfBytes = await generatePdf(bill, products: products);
    await Printing.layoutPdf(
      onLayout: (PdfPageFormat format) async => pdfBytes,
      name: 'Invoice_${bill.id ?? 'New'}.pdf',
    );
  }

  static Future<void> shareReceipt(Bill bill, {List<Product>? products}) async {
    final pdfBytes = await generatePdf(bill, products: products);
    await Printing.sharePdf(
      bytes: pdfBytes,
      filename: 'Invoice_${bill.id ?? 'New'}.pdf',
    );
  }
}
